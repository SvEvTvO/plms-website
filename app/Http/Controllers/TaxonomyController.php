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

        // Tarik data HANYA milik user yang sedang login
        $groups = CategoryGroup::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $categories = Category::with('group')->where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $tags = Tag::with('category')->where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        return view('taxonomy.index', compact('groups', 'categories', 'tags'));
    }

    // ==========================================
    // LOGIKA GRUP (KATEGORI BESAR)
    // ==========================================
    public function storeGroup(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        CategoryGroup::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'icon' => $request->icon ?? 'folder'
        ]);

        return back()->with('success', 'Grup berhasil ditambahkan!');
    }

    public function destroyGroup(CategoryGroup $group)
    {
        if ($group->user_id !== auth()->id()) abort(403);
        $group->delete();
        return back()->with('success', 'Grup berhasil dihapus!');
    }

    // ==========================================
    // LOGIKA KATEGORI SPESIFIK
    // ==========================================
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_group_id' => 'nullable|exists:category_groups,id',
            'icon' => 'nullable|string',
            'color' => 'nullable|string'
        ]);

        Category::create([
            'user_id' => auth()->id(),
            'category_group_id' => $request->category_group_id,
            'name' => $request->name,
            // Tambahkan uniqid agar nama yang sama persis tidak pernah bentrok slug-nya
            'slug' => Str::slug($request->name) . '-' . uniqid(), 
            'icon' => $request->icon ?? 'folder',
            'color' => $request->color ?? '#3b82f6'
        ]);
        
        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->user_id !== auth()->id()) abort(403);
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    // ==========================================
    // LOGIKA TAG
    // ==========================================
    public function storeTag(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id'
        ]);

        Tag::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'status' => 'approved' // Tag pribadi langsung disetujui
        ]);
        
        return back()->with('success', 'Tag berhasil ditambahkan!');
    }

    public function destroyTag(Tag $tag)
    {
        if ($tag->user_id !== auth()->id()) abort(403);
        $tag->delete();
        return back()->with('success', 'Tag berhasil dihapus!');
    }
}
