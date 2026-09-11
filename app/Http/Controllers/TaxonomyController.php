<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaxonomyController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $groups = \App\Models\CategoryGroup::where('user_id', $userId)
            ->with(['categories' => function ($query) use ($userId) {
                $query->orderBy('name', 'asc')->with(['tags' => function ($q) use ($userId) {
                    $q->where('user_id', $userId)->orderBy('name', 'asc');
                }]);
            }])
            ->orderBy('name', 'asc')
            ->get();

        $ungroupedCategories = \App\Models\Category::where('user_id', $userId)
            ->whereNull('category_group_id')
            ->with(['tags' => function ($q) use ($userId) {
                $q->where('user_id', $userId)->orderBy('name', 'asc');
            }])
            ->orderBy('name', 'asc')
            ->get();

        $recentTags = \App\Models\Tag::where('user_id', $userId)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $allCategoriesFlat = \App\Models\Category::where('user_id', $userId)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return view('taxonomy.index', compact('groups', 'ungroupedCategories', 'recentTags', 'allCategoriesFlat'));
    }

    // ==========================================
    // 1. LOGIKA GRUP (KATEGORI BESAR)
    // ==========================================
    public function storeGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50'
        ]);

        CategoryGroup::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'icon' => $request->icon ?? 'layout-grid'
        ]);

        return back()->with('success', 'Grup berhasil ditambahkan!');
    }

    public function updateGroup(Request $request, CategoryGroup $group)
    {
        if ($group->user_id !== auth()->id()) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50'
        ]);

        $group->update([
            'name' => $request->name,
            'icon' => $request->icon ?? 'layout-grid'
        ]);

        return back()->with('success', 'Grup berhasil diperbarui!');
    }

    public function destroyGroup(CategoryGroup $group)
    {
        if ($group->user_id !== auth()->id()) abort(403);
        $group->delete();
        return back()->with('success', 'Grup berhasil dihapus!');
    }

    // ==========================================
    // 2. LOGIKA KATEGORI SPESIFIK
    // ==========================================
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:category_groups,id', // Menggunakan parent_id dari Modal
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20'
        ]);

        Category::create([
            'user_id' => auth()->id(),
            'category_group_id' => $request->parent_id, // Disambungkan ke relasi grup
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'icon' => $request->icon ?? 'folder',
            'color' => $request->color ?? '#3b82f6'
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function updateCategory(Request $request, Category $category)
    {
        if ($category->user_id !== auth()->id()) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:category_groups,id',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20'
        ]);

        $category->update([
            'category_group_id' => $request->parent_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'icon' => $request->icon ?? 'folder',
            'color' => $request->color ?? $category->color
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->user_id !== auth()->id()) abort(403);
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    // ==========================================
    // 3. LOGIKA TAG
    // ==========================================
    public function storeTag(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'parent_id' => 'required|exists:categories,id' // Menggunakan parent_id
        ]);

        Tag::create([
            'user_id' => auth()->id(),
            'category_id' => $request->parent_id, // Disambungkan ke relasi kategori
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'status' => 'approved' 
        ]);

        return back()->with('success', 'Tag berhasil ditambahkan!');
    }

    public function updateTag(Request $request, Tag $tag)
    {
        if ($tag->user_id !== auth()->id()) abort(403);

        $request->validate([
            'name' => 'required|string|max:50',
            'parent_id' => 'required|exists:categories,id'
        ]);

        $tag->update([
            'category_id' => $request->parent_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
        ]);

        return back()->with('success', 'Tag berhasil diperbarui!');
    }

    public function destroyTag(Tag $tag)
    {
        if ($tag->user_id !== auth()->id()) abort(403);
        $tag->delete();
        return back()->with('success', 'Tag berhasil dihapus!');
    }
}
