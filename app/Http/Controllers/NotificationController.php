<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Halaman Semua Riwayat Notifikasi
    public function index()
    {
        // Ambil semua notifikasi (baik yang belum maupun sudah dibaca), paginasi 15 per halaman
        $notifications = auth()->user()->notifications()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }
}
