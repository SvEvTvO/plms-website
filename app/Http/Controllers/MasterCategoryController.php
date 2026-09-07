<?php

namespace App\Http\Controllers;

use App\Models\MasterCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterCategoryController extends Controller
{
    public function create()
    {
        return view('master-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            // PERBAIKAN: Gunakan format Array agar Regex yang memiliki tanda '|' tidak error
            'color' => ['nullable', 'string', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'sub_categories' => 'nullable|array',
            'sub_categories.*' => 'nullable|string|max:150',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['color'] = $validated['color'] ?? '#0ea5e9'; // Default Blue Tailwind

        // 1. Buat Master Kategori
        $master = MasterCategory::create([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'icon' => $validated['icon'],
            'color' => $validated['color'],
            'description' => $validated['description'],
        ]);

        // 2. Buat Sub-kategorinya sekaligus (jika ada)
        if (!empty($validated['sub_categories'])) {
            foreach ($validated['sub_categories'] as $subName) {
                if (trim($subName) !== '') {
                    $master->categories()->create([
                        'user_id' => auth()->id(),
                        'name' => $subName,
                        'slug' => Str::slug($subName . '-' . time()),
                    ]);
                }
            }
        }

        return redirect()->route('categories.index')->with('success', 'Folder Kategori berhasil dibuat!');
    }

    public function edit(MasterCategory $masterCategory)
    {
        if ($masterCategory->user_id !== auth()->id()) { abort(403); }
        return view('master-categories.edit', compact('masterCategory'));
    }

    public function update(Request $request, MasterCategory $masterCategory)
    {
        if ($masterCategory->user_id !== auth()->id()) { abort(403); }

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            // PERBAIKAN: Gunakan format Array di sini juga
            'color' => ['nullable', 'string', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'sub_categories' => 'nullable|array',
            'sub_categories.*' => 'nullable|string|max:150',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['color'] = $validated['color'] ?? '#0ea5e9';

        // 1. Update Master
        $masterCategory->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'icon' => $validated['icon'],
            'color' => $validated['color'],
            'description' => $validated['description'],
        ]);

        // 2. Tambah Sub-kategori baru (Hanya insert, tidak menghapus yang lama)
        if (!empty($validated['sub_categories'])) {
            foreach ($validated['sub_categories'] as $subName) {
                if (trim($subName) !== '') {
                    $masterCategory->categories()->create([
                        'user_id' => auth()->id(),
                        'name' => $subName,
                        'slug' => Str::slug($subName . '-' . time()),
                    ]);
                }
            }
        }

        return redirect()->route('categories.index')->with('success', 'Folder Kategori berhasil diperbarui!');
    }
}
