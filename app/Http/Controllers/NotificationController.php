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

    /**
     * Tandai satu notifikasi sudah dibaca
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->unreadNotifications->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        // UBAH BAGIAN INI: Kembalikan user ke halaman sebelumnya (bukan return response()->json)
        return back();
    }

    /**
     * Tandai SEMUA notifikasi sudah dibaca
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        // UBAH BAGIAN INI JUGA: Kembalikan user ke halaman sebelumnya
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
    /**
     * Menghapus 1 notifikasi spesifik.
     */
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * Membersihkan (menghapus) semua notifikasi pengguna.
     */
    public function clearAll()
    {
        auth()->user()->notifications()->delete();

        return back()->with('success', 'Seluruh riwayat notifikasi berhasil dibersihkan.');
    }
}
