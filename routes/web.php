<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaxonomyController;
use App\Http\Controllers\TaxonomyRequestController;
use App\Http\Controllers\NotificationController;




/*
|--------------------------------------------------------------------------
| PUBLIK / KOMUNITAS (Tanpa perlu login)
|--------------------------------------------------------------------------
*/
// 1. Landing Page Utama
Route::get('/', function () {
    return view('welcome');
});

// 2. Halaman Explore (Daftar Website Publik)
Route::get('/explore', [ExploreController::class, 'index'])->name('explore');


/*
|--------------------------------------------------------------------------
| AREA PRIVAT (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // 1. Dashboard Pribadi
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Simpan Website (Bookmark)
    Route::get('/bookmarks/create', [BookmarkController::class, 'create'])->name('bookmarks.create');
    Route::post('/bookmarks', [BookmarkController::class, 'store'])->name('bookmarks.store');

    // 3. API Pencarian Tag (Untuk form auto-complete)
    Route::get('/tags/search', [TagController::class, 'search'])->name('tags.search');

    // 4. Profil Akun
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- MANAJEMEN TAKSONOMI PRIBADI ---
    Route::get('/taxonomy', [TaxonomyController::class, 'index'])->name('taxonomy.index');

    // Grup
    Route::post('/taxonomy/group', [TaxonomyController::class, 'storeGroup'])->name('taxonomy.group.store');
    Route::delete('/taxonomy/group/{group}', [TaxonomyController::class, 'destroyGroup'])->name('taxonomy.group.destroy');

    // Kategori
    Route::post('/taxonomy/category', [TaxonomyController::class, 'storeCategory'])->name('taxonomy.category.store');
    Route::delete('/taxonomy/category/{category}', [TaxonomyController::class, 'destroyCategory'])->name('taxonomy.category.destroy');

    // Tag
    Route::post('/taxonomy/tag', [TaxonomyController::class, 'storeTag'])->name('taxonomy.tag.store');
    Route::delete('/taxonomy/tag/{tag}', [TaxonomyController::class, 'destroyTag'])->name('taxonomy.tag.destroy');

    Route::get('/dashboard/published', [DashboardController::class, 'published'])->name('dashboard.published');

    Route::patch('/bookmarks/{bookmark}/publish', [BookmarkController::class, 'publish'])->name('bookmarks.publish');
    Route::patch('/bookmarks/{bookmark}/unpublish', [BookmarkController::class, 'unpublish'])->name('bookmarks.unpublish');

    Route::get('/bookmarks/{bookmark}/edit', [BookmarkController::class, 'edit'])->name('bookmarks.edit');
    Route::put('/bookmarks/{bookmark}', [BookmarkController::class, 'update'])->name('bookmarks.update');

    // --- PENGAJUAN TAKSONOMI PUBLIK ---
    Route::get('/taxonomy-requests', [TaxonomyRequestController::class, 'index'])->name('taxonomy.requests.index');
    Route::post('/taxonomy-requests', [TaxonomyRequestController::class, 'store'])->name('taxonomy.requests.store');


    // --- NOTIFIKASI ---
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');

    // --- NOTIFIKASI ---
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index'); // <-- Rute Baru
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');

    // RUTE TESTING (Hapus nanti jika panel admin sudah dibuat)
    Route::get('/test-notif', function() {
        auth()->user()->notify(new \App\Notifications\TaxonomyRequestProcessed(
            'approved', 'tag', 'React Native', '<span class="font-extrabold text-slate-900">Pengajuan Diterima!</span> Tag <span class="font-bold text-primary">#React Native</span> yang kamu ajukan telah disetujui.'
        ));
        auth()->user()->notify(new \App\Notifications\TaxonomyRequestProcessed(
            'rejected', 'group', 'Skripsi', '<span class="font-extrabold text-slate-900">Pengajuan Ditolak.</span> Grup <span class="font-bold text-primary">Skripsi</span> ditolak karena terlalu spesifik.'
        ));
        return redirect()->back()->with('success', '2 Notifikasi testing berhasil dikirim!');
    });
});

// Sistem Autentikasi (Bawaan Breeze)
require __DIR__.'/auth.php';
