<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\Tag;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $allCategories = Category::with('group')->orderBy('name', 'asc')->get();

        // 1. Setup Query Dasar (Hanya ambil yang Publik)
        $query = Website::whereHas('bookmarks', function ($q) {
            $q->where('is_public', true);
        })->with(['publicBookmarks.tags', 'publicBookmarks.category'])
          ->withCount(['bookmarks' => function ($q) {
              $q->where('is_public', true);
          }])
          ->when($userId, function($q) use ($userId) {
              // Cek apakah user yang login sudah menyimpan website ini
              $q->withExists(['bookmarks as is_saved_by_user' => function($bQ) use ($userId) {
                  $bQ->where('user_id', $userId);
              }]);
          });

        // 2. Terapkan Filter Grup / Kategori Besar
        if ($request->filled('group') && !$request->filled('website')) {
            if ($request->group === 'Kategori Lainnya') {
                $query->whereHas('bookmarks', function ($q) {
                    $q->where('is_public', true)->whereHas('category', function ($catQ) {
                        $catQ->whereNull('category_group_id');
                    });
                });
            } else {
                $query->whereHas('bookmarks', function ($q) use ($request) {
                    $q->where('is_public', true)->whereHas('category', function ($catQ) use ($request) {
                        $catQ->whereHas('group', function ($gQ) use ($request) {
                            $gQ->where('name', $request->group);
                        });
                    });
                });
            }
        }

        // 3. Terapkan Filter Kategori Spesifik (Mengabaikan grup jika kategori spesifik dipilih)
        if ($request->filled('category') && !$request->filled('website')) {
            $query->whereHas('bookmarks', function ($q) use ($request) {
                $q->where('is_public', true)
                  ->whereHas('category', function ($catQ) use ($request) {
                      $catQ->where('slug', $request->category);
                  });
            });
        }

        // 4. Terapkan Filter Tags
        if ($request->filled('tags') && is_array($request->tags) && !$request->filled('website')) {
            foreach ($request->tags as $tagSlug) {
                $query->whereHas('bookmarks', function ($q) use ($tagSlug) {
                    $q->where('is_public', true)
                      ->whereHas('tags', function ($tagQ) use ($tagSlug) {
                          $tagQ->where('slug', $tagSlug);
                      });
                });
            }
        }

        // 5. Terapkan Filter Website Tertentu (PRIORITAS TERTINGGI, MENABRAK FILTER LAIN)
        if ($request->filled('website')) {
            $query->where('websites.id', $request->website);
        }

        // 6. Pencarian Teks Bebas
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('websites.title', 'ilike', '%' . $searchTerm . '%')
                  ->orWhere('websites.description', 'ilike', '%' . $searchTerm . '%');
            });
        }

        $websites = $query->orderBy('bookmarks_count', 'desc')
                          ->orderBy('websites.created_at', 'desc')
                          ->paginate(15)
                          ->withQueryString();

        // --- DATA UNTUK UI SEARCH BAR & MENU ---
        $groupedCategories = CategoryGroup::with('categories')->has('categories')->get();
        $otherCats = $allCategories->whereNull('category_group_id');

        if ($otherCats->isNotEmpty()) {
            $groupedCategories->push((object)[
                'name' => 'Kategori Lainnya',
                'icon' => 'folder',
                'categories' => $otherCats
            ]);
        }

        // B. Data JSON untuk Live Search
        $searchData = [
            'categories' => $allCategories->map(fn($c) => [
                'id' => $c->slug,
                'name' => $c->name,
                'type' => 'category',
                'group_name' => $c->group->name ?? 'Kategori Lainnya'
            ])->toArray(),

            'tags' => Tag::with('category')->where('status', 'approved')->get()->map(fn($t) => [
                'id' => $t->slug,
                'name' => $t->name,
                'type' => 'tag',
                'category_slug' => $t->category->slug ?? null
            ])->toArray(),

            'websites' => Website::whereHas('bookmarks', fn($q) => $q->where('is_public', true))
                                 ->get()->unique('title')
                                 ->map(fn($w) => ['id' => $w->id, 'name' => $w->title, 'type' => 'website'])
                                 ->values()->toArray()
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

        return view('explore.index', compact(
            'websites', 'groupedCategories', 'searchData', 'selectedCategory', 'selectedTags', 'selectedWebsite', 'searchQueryText', 'selectedGroup'
        ));
    }
}
