<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\TaxonomyRequest;
use Illuminate\Http\Request;

class TaxonomyRequestController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // 1. Data PUBLIK (Untuk Pilihan Induk / Parent)
        $publicGroups = \App\Models\CategoryGroup::whereNull('user_id')->orderBy('name', 'asc')->get(['id', 'name']);
        $publicCategories = \App\Models\Category::whereNull('user_id')->orderBy('name', 'asc')->get(['id', 'name']);

        // 2. Data PRIBADI (Untuk Auto-fill form pengajuan)
        $privateGroups = \App\Models\CategoryGroup::where('user_id', $userId)->orderBy('name', 'asc')->get(['id', 'name']);
        $privateCategories = \App\Models\Category::where('user_id', $userId)->orderBy('name', 'asc')->get(['id', 'name']);
        $privateTags = \App\Models\Tag::where('user_id', $userId)->orderBy('name', 'asc')->get(['id', 'name']);

        // 3. Riwayat Pengajuan User
        $requests = \App\Models\TaxonomyRequest::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        return view('taxonomy.requests', compact(
            'publicGroups', 'publicCategories',
            'privateGroups', 'privateCategories', 'privateTags',
            'requests'
        ));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'type' => 'required|in:tag,category,group',
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer' // <-- Terima input parent_id
        ]);

        // 2. Simpan ke Database
        \App\Models\TaxonomyRequest::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'name' => $request->name,
            'target_parent_id' => $request->parent_id, // <-- Petakan ke kolom DB
            'status' => 'pending'
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim! Silakan tunggu peninjauan dari Admin.');
    }

    public function update(Request $request, \App\Models\TaxonomyRequest $taxonomyRequest)
    {
        // Pastikan hanya pemilik yang bisa ngedit, dan statusnya masih pending
        if ($taxonomyRequest->user_id !== auth()->id() || $taxonomyRequest->status !== 'pending') {
            abort(403, 'Akses ditolak atau pengajuan sudah diproses.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer'
        ]);

        // Cukup perbarui nama dan parent
        $taxonomyRequest->update([
            'name' => $request->name,
            'target_parent_id' => $request->parent_id // <-- Petakan ke kolom DB
        ]);

        return back()->with('success', 'Pengajuan berhasil diperbarui!');
    }

    public function destroy(\App\Models\TaxonomyRequest $taxonomyRequest)
    {
        if ($taxonomyRequest->user_id !== auth()->id() || $taxonomyRequest->status !== 'pending') {
            abort(403, 'Akses ditolak atau pengajuan sudah diproses.');
        }

        $taxonomyRequest->delete();

        return back()->with('success', 'Pengajuan berhasil dibatalkan dan dihapus!');
    }
}
