<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\Tag;
use App\Models\Website;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        // 1. Setup Query Dasar (HANYA MILIK USER YANG LOGIN)
        $query = Bookmark::with(['website', 'category', 'tags'])
                         ->where('user_id', $userId);

        // 2. Terapkan Filter Grup / Kategori Besar
        if ($request->filled('group') && !$request->filled('website')) {
            if ($request->group === 'Kategori Lainnya') {
                $query->whereHas('category', function ($q) {
                    $q->whereNull('category_group_id');
                });
            } else {
                $query->whereHas('category.group', function ($q) use ($request) {
                    $q->where('name', $request->group);
                });
            }
        }

        // 3. Terapkan Filter Kategori Spesifik
        if ($request->filled('category') && !$request->filled('website')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 4. Terapkan Filter Tags
        if ($request->filled('tags') && is_array($request->tags) && !$request->filled('website')) {
            foreach ($request->tags as $tagSlug) {
                $query->whereHas('tags', function ($q) use ($tagSlug) {
                    $q->where('slug', $tagSlug);
                });
            }
        }

        // 5. Terapkan Filter Website Tertentu
        if ($request->filled('website')) {
            $query->where('website_id', $request->website);
        }

        // 6. Pencarian Teks Bebas (Bisa nyari Judul Custom atau Judul Asli Web)
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('custom_title', 'ilike', '%' . $searchTerm . '%')
                  ->orWhereHas('website', function($wQ) use ($searchTerm) {
                      $wQ->where('title', 'ilike', '%' . $searchTerm . '%')
                         ->orWhere('description', 'ilike', '%' . $searchTerm . '%');
                  });
            });
        }

        $bookmarks = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // --- DATA UNTUK UI SEARCH BAR & MENU (HANYA YANG USER PUNYA) ---

        // Ambil ID kategori yang pernah disimpan user
        $userCategoryIds = Bookmark::where('user_id', $userId)->pluck('category_id')->unique();
        $allCategories = Category::with('group')->whereIn('id', $userCategoryIds)->orderBy('name', 'asc')->get();

        $groupedCategories = CategoryGroup::with(['categories' => function($q) use ($userCategoryIds) {
            $q->whereIn('id', $userCategoryIds);
        }])->whereHas('categories', function($q) use ($userCategoryIds) {
            $q->whereIn('id', $userCategoryIds);
        })->get();

        $otherCats = $allCategories->whereNull('category_group_id');
        if ($otherCats->isNotEmpty()) {
            $groupedCategories->push((object)[
                'name' => 'Kategori Lainnya',
                'icon' => 'folder',
                'categories' => $otherCats
            ]);
        }

        // B. Data JSON untuk Live Search (HANYA MILIK USER)
        $userTags = Tag::with('category')->whereHas('bookmarks', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('status', 'approved')->get();

        $userWebsites = Website::whereHas('bookmarks', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->get();

        $searchData = [
            'categories' => $allCategories->map(fn($c) => [
                'id' => $c->slug, 'name' => $c->name, 'type' => 'category', 'group_name' => $c->group->name ?? 'Kategori Lainnya'
            ])->toArray(),

            'tags' => $userTags->map(fn($t) => [
                'id' => $t->slug, 'name' => $t->name, 'type' => 'tag', 'category_slug' => $t->category->slug ?? null
            ])->toArray(),

            'websites' => $userWebsites->map(fn($w) => [
                'id' => $w->id, 'name' => $w->title, 'type' => 'website'
            ])->toArray()
        ];

        // C. Membaca Status Pencarian Saat Ini
        $selectedGroup = $request->group;
        $selectedCategory = null;
        if ($request->category) {
            $cat = $allCategories->firstWhere('slug', $request->category);
            if ($cat) {
                $selectedCategory = ['id' => $cat->slug, 'name' => $cat->name, 'type' => 'category', 'group_name' => $cat->group->name ?? 'Kategori Lainnya'];
                $selectedGroup = $cat->group->name ?? 'Kategori Lainnya';
            }
        }

        $selectedTags = [];
        if ($request->tags && is_array($request->tags)) {
            $tags = Tag::whereIn('slug', $request->tags)->get();
            foreach ($tags as $t) {
                $selectedTags[] = ['id' => $t->slug, 'name' => $t->name, 'type' => 'tag'];
            }
        }

        $selectedWebsite = null;
        if ($request->website) {
            $web = Website::find($request->website);
            if ($web) $selectedWebsite = ['id' => $web->id, 'name' => $web->title, 'type' => 'website'];
        }

        $searchQueryText = $request->q ?? '';

        // Ambil kategori khusus Admin/Global untuk Modal Publish
        $adminCategories = Category::with('group')->whereNull('user_id')->orderBy('name', 'asc')->get();

        // Ambil tag khusus Admin/Global untuk Modal Publish
        $adminTags = Tag::whereNull('user_id')->where('status', 'approved')->get(['id', 'name', 'category_id']);

        return view('dashboard.index', compact(
            'bookmarks', 'groupedCategories', 'searchData', 'selectedCategory', 'selectedTags', 'selectedWebsite', 'searchQueryText', 'selectedGroup', 'adminCategories', 'adminTags'
        ));
    }

    public function published(Request $request)
    {
        $userId = auth()->id();

        // 1. Setup Query Dasar (HANYA MILIK USER YANG LOGIN & BERSTATUS PUBLIK)
        $query = Bookmark::with(['website', 'category', 'tags'])
                         ->where('user_id', $userId)
                         ->where('is_public', true); // <--- KUNCI UTAMANYA DI SINI

        // 2. Terapkan Filter Grup
        if ($request->filled('group') && !$request->filled('website')) {
            if ($request->group === 'Kategori Lainnya') {
                $query->whereHas('category', fn($q) => $q->whereNull('category_group_id'));
            } else {
                $query->whereHas('category.group', fn($q) => $q->where('name', $request->group));
            }
        }

        // 3. Terapkan Filter Kategori Spesifik
        if ($request->filled('category') && !$request->filled('website')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        // 4. Terapkan Filter Tags
        if ($request->filled('tags') && is_array($request->tags) && !$request->filled('website')) {
            foreach ($request->tags as $tagSlug) {
                $query->whereHas('tags', fn($q) => $q->where('slug', $tagSlug));
            }
        }

        // 5. Terapkan Filter Website Tertentu
        if ($request->filled('website')) {
            $query->where('website_id', $request->website);
        }

        // 6. Pencarian Teks Bebas
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('custom_title', 'ilike', '%' . $searchTerm . '%')
                  ->orWhereHas('website', function($wQ) use ($searchTerm) {
                      $wQ->where('title', 'ilike', '%' . $searchTerm . '%')
                         ->orWhere('description', 'ilike', '%' . $searchTerm . '%');
                  });
            });
        }

        $bookmarks = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // --- DATA UNTUK UI SEARCH BAR & MENU (HANYA DARI DATA PUBLIK) ---
        $userCategoryIds = Bookmark::where('user_id', $userId)->where('is_public', true)->pluck('category_id')->unique();
        $allCategories = Category::with('group')->whereIn('id', $userCategoryIds)->orderBy('name', 'asc')->get();

        $groupedCategories = CategoryGroup::with(['categories' => function($q) use ($userCategoryIds) {
            $q->whereIn('id', $userCategoryIds);
        }])->whereHas('categories', function($q) use ($userCategoryIds) {
            $q->whereIn('id', $userCategoryIds);
        })->get();

        $otherCats = $allCategories->whereNull('category_group_id');
        if ($otherCats->isNotEmpty()) {
            $groupedCategories->push((object)[
                'name' => 'Kategori Lainnya',
                'icon' => 'folder',
                'categories' => $otherCats
            ]);
        }

        $userTags = Tag::with('category')->whereHas('bookmarks', function($q) use ($userId) {
            $q->where('user_id', $userId)->where('is_public', true);
        })->where('status', 'approved')->get();

        $userWebsites = Website::whereHas('bookmarks', function($q) use ($userId) {
            $q->where('user_id', $userId)->where('is_public', true);
        })->get();

        $searchData = [
            'categories' => $allCategories->map(fn($c) => ['id' => $c->slug, 'name' => $c->name, 'type' => 'category', 'group_name' => $c->group->name ?? 'Kategori Lainnya'])->toArray(),
            'tags' => $userTags->map(fn($t) => ['id' => $t->slug, 'name' => $t->name, 'type' => 'tag', 'category_slug' => $t->category->slug ?? null])->toArray(),
            'websites' => $userWebsites->map(fn($w) => ['id' => $w->id, 'name' => $w->title, 'type' => 'website'])->toArray()
        ];

        // Status Pencarian
        $selectedGroup = $request->group;
        $selectedCategory = null;
        if ($request->category) {
            $cat = $allCategories->firstWhere('slug', $request->category);
            if ($cat) {
                $selectedCategory = ['id' => $cat->slug, 'name' => $cat->name, 'type' => 'category', 'group_name' => $cat->group->name ?? 'Kategori Lainnya'];
                $selectedGroup = $cat->group->name ?? 'Kategori Lainnya';
            }
        }

        $selectedTags = [];
        if ($request->tags && is_array($request->tags)) {
            $tags = Tag::whereIn('slug', $request->tags)->get();
            foreach ($tags as $t) { $selectedTags[] = ['id' => $t->slug, 'name' => $t->name, 'type' => 'tag']; }
        }

        $selectedWebsite = null;
        if ($request->website) {
            $web = Website::find($request->website);
            if ($web) $selectedWebsite = ['id' => $web->id, 'name' => $web->title, 'type' => 'website'];
        }

        $searchQueryText = $request->q ?? '';

        return view('dashboard.published', compact(
            'bookmarks', 'groupedCategories', 'searchData', 'selectedCategory', 'selectedTags', 'selectedWebsite', 'searchQueryText', 'selectedGroup'
        ));
    }
}
