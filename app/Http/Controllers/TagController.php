<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->get('q');
        $categoryId = $request->get('category_id'); // Tangkap ID Kategori dari form

        // Jika tidak ada pencarian atau kategori belum dipilih, kembalikan array kosong
        if (empty($search) || empty($categoryId)) {
            return response()->json([]);
        }

        $tags = Tag::where('category_id', $categoryId) // WAJIB sesuai kategori
            ->where('status', 'approved')
            ->where('name', 'ilike', '%' . $search . '%')
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($tags);
    }
}
