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
        // 1. Ambil riwayat pengajuan milik user yang login
        $requests = TaxonomyRequest::with(['targetGroup', 'targetCategory'])
                                   ->where('user_id', auth()->id())
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        // 2. Ambil data GLOBAL (milik Admin) untuk pilihan Induk
        $adminGroups = CategoryGroup::whereNull('user_id')->orderBy('name', 'asc')->get();
        $adminCategories = Category::with('group')->whereNull('user_id')->orderBy('name', 'asc')->get();

        return view('taxonomy.requests', compact('requests', 'adminGroups', 'adminCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:group,category,tag',
            'name' => 'required|string|max:255',
            'target_parent_id' => 'nullable|integer',
            'icon' => 'nullable|string',
            'color' => 'nullable|string'
        ]);

        // Cek Anti-Spam: Jangan boleh ajukan nama yang sama persis jika masih 'pending'
        $exists = TaxonomyRequest::where('user_id', auth()->id())
            ->where('type', $request->type)
            ->where('name', $request->name)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'Kamu sudah mengajukan nama ini dan sedang menunggu persetujuan Admin.']);
        }

        TaxonomyRequest::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'name' => $request->name,
            'target_parent_id' => $request->target_parent_id,
            'icon' => $request->icon,
            'color' => $request->color,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim! Silakan tunggu peninjauan dari Admin.');
    }
}
