<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Statistics
        $stats = [
            'total_websites' => $user->websites()->count(),
            'total_categories' => $user->categories()->count(),
            'total_favorites' => $user->websites()->where('is_favorite', true)->count(),
        ];

        // 2. Quick Categories (Ambil 6 Master Category teratas)
        $quickCategories = $user->masterCategories()
            ->withCount('categories') // Hitung jumlah sub-kategori sebagai indikator tambahan
            ->orderByDesc('categories_count')
            ->take(6)
            ->get();

        // 3. Recently Added (Ambil 4 website terbaru)
        $recentWebsites = $user->websites()
            ->with(['categories', 'tags'])
            ->latest()
            ->take(4)
            ->get();

        return view('dashboard', compact('stats', 'quickCategories', 'recentWebsites'));
    }
}
