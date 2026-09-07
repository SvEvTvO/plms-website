<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        // Ambil hanya website yang di-favorite oleh user yang login
        $websites = auth()->user()->websites()
            ->where('is_favorite', true)
            ->with(['categories', 'tags'])
            ->latest()
            ->paginate(24);

        return view('favorites.index', compact('websites'));
    }
}
