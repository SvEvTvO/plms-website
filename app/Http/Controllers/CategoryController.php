<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterCategory;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        // Mengambil Master Category milik user beserta sub-kategori dan jumlah websitenya
        $masterCategories = auth()->user()->masterCategories()
            ->with(['categories' => function($query) {
                $query->withCount('websites')->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('categories.index', compact('masterCategories'));
    }

    // ... (method create, store, edit, update, destroy akan menyusul)

    public function create(Request $request)
    {
        // Ambil semua master category milik user untuk pilihan dropdown
        $masterCategories = auth()->user()->masterCategories()->orderBy('name')->get();

        // Menangkap master_id dari URL (agar otomatis terpilih jika dari tombol card)
        $selectedMasterId = $request->query('master_id');

        return view('categories.create', compact('masterCategories', 'selectedMasterId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'master_category_id' => 'required|exists:master_categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
        ]);

        // Validasi keamanan ekstra: pastikan master_category milik user tersebut
        $master = auth()->user()->masterCategories()->findOrFail($validated['master_category_id']);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category saved successfully.');
    }


    public function edit(Category $category)
    {
        // Pastikan hanya pemilik yang dapat mengedit
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        // Ambil semua master category milik user untuk pilihan dropdown
        $masterCategories = auth()->user()->masterCategories()->orderBy('name')->get();

        return view('categories.edit', compact('category', 'masterCategories'));
    }

    public function update(Request $request, Category $category)
    {
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'master_category_id' => 'required|exists:master_categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
        ]);

        // Validasi keamanan ekstra: pastikan master_category milik user tersebut
        auth()->user()->masterCategories()->findOrFail($validated['master_category_id']);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        // Pastikan keamanan milik user
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        // Cek apakah ada website yang menggunakan kategori ini
        $websiteCount = $category->websites()->count();
        if ($websiteCount > 0) {
            return back()->with('error', "Cannot delete category. It is currently used by {$websiteCount} websites. Please reassign them first.");
        }

        $category->delete();

        return back()->with('success', 'Category deleted successfully.');
    }
}
