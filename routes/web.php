<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaxonomyController;
use App\Http\Controllers\TaxonomyRequestController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

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

    // --- DASHBOARD & MANAJEMEN BOOKMARK ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/published', [DashboardController::class, 'published'])->name('dashboard.published');

    // Bookmark Create, Store, Edit, Update
    Route::get('/bookmarks/create', [BookmarkController::class, 'create'])->name('bookmarks.create');
    Route::post('/bookmarks', [BookmarkController::class, 'store'])->name('bookmarks.store');
    Route::get('/bookmarks/{bookmark}/edit', [BookmarkController::class, 'edit'])->name('bookmarks.edit');
    Route::put('/bookmarks/{bookmark}', [BookmarkController::class, 'update'])->name('bookmarks.update');

    // Publish & Unpublish Bookmark
    Route::patch('/bookmarks/{bookmark}/publish', [BookmarkController::class, 'publish'])->name('bookmarks.publish');
    Route::patch('/bookmarks/{bookmark}/unpublish', [BookmarkController::class, 'unpublish'])->name('bookmarks.unpublish');

    // API Pencarian Tag (Auto-complete)
    Route::get('/tags/search', [TagController::class, 'search'])->name('tags.search');


    // --- MANAJEMEN TAKSONOMI PRIBADI ---
    Route::get('/taxonomy', [TaxonomyController::class, 'index'])->name('taxonomy.index');

    // Grup Kategori
    Route::post('/groups', [TaxonomyController::class, 'storeGroup'])->name('taxonomy.group.store');
    Route::put('/groups/{group}', [TaxonomyController::class, 'updateGroup'])->name('taxonomy.group.update');
    Route::delete('/groups/{group}', [TaxonomyController::class, 'destroyGroup'])->name('taxonomy.group.destroy');

    // Kategori Spesifik
    Route::post('/categories', [TaxonomyController::class, 'storeCategory'])->name('taxonomy.category.store');
    Route::put('/categories/{category}', [TaxonomyController::class, 'updateCategory'])->name('taxonomy.category.update');
    Route::delete('/categories/{category}', [TaxonomyController::class, 'destroyCategory'])->name('taxonomy.category.destroy');

    // Tag (Kata Kunci)
    Route::post('/tags', [TaxonomyController::class, 'storeTag'])->name('taxonomy.tag.store');
    Route::put('/tags/{tag}', [TaxonomyController::class, 'updateTag'])->name('taxonomy.tag.update');
    Route::delete('/tags/{tag}', [TaxonomyController::class, 'destroyTag'])->name('taxonomy.tag.destroy');


    // --- PENGAJUAN TAKSONOMI PUBLIK ---
    Route::get('/taxonomy-requests', [TaxonomyRequestController::class, 'index'])->name('taxonomy.requests.index');
    Route::post('/taxonomy-requests', [TaxonomyRequestController::class, 'store'])->name('taxonomy.requests.store');
    Route::put('/taxonomy-requests/{taxonomyRequest}', [TaxonomyRequestController::class, 'update'])->name('taxonomy.requests.update');
    Route::delete('/taxonomy-requests/{taxonomyRequest}', [TaxonomyRequestController::class, 'destroy'])->name('taxonomy.requests.destroy');


    // --- NOTIFIKASI ---
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [NotificationController::class, 'clearAll'])->name('notifications.clearAll');

    // --- PROFIL AKUN ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // --- RUTE TESTING (Hapus nanti jika panel admin sudah jadi) ---
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
