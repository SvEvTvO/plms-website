<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterCategoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;

Route::get('/debug-hash-test', function () {
    $result = @password_hash('test123', PASSWORD_BCRYPT, ['cost' => 12]);

    return response()->json([
        'php_version'          => PHP_VERSION,
        'openssl_loaded'       => extension_loaded('openssl'),
        'sodium_loaded'        => extension_loaded('sodium'),
        'hash_loaded'          => extension_loaded('hash'),
        'crypt_blowfish'       => defined('CRYPT_BLOWFISH') ? CRYPT_BLOWFISH : 'undefined',
        'password_algos'       => function_exists('password_algos') ? password_algos() : 'function tidak ada',
        'password_hash_result' => $result === false
            ? 'GAGAL (false)'
            : 'BERHASIL: ' . substr($result, 0, 15) . '...',
        'last_error'           => error_get_last(),
        'config_hash_driver'   => config('hashing.driver'),
    ]);
});


// 1. Tampilkan Landing Page untuk route '/'
Route::get('/', function () {
    return view('welcome');
});

// 2. Dasbor (Hanya bisa diakses jika sudah login)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// 3. Grup Route dengan proteksi Auth (Harus Login)
Route::middleware('auth')->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // PLMS Routes (Master Category & Category)
    Route::resource('master-categories', MasterCategoryController::class)->except(['show']);
    Route::resource('categories', CategoryController::class);

    // Websites Route
    Route::resource('websites', WebsiteController::class);

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');

    // Custom Website Actions (Favorite, Archive, Auto-Fetch Meta)
    Route::post('/websites/{website}/favorite', [WebsiteController::class, 'toggleFavorite'])->name('websites.favorite');
    Route::post('/websites/{website}/archive', [WebsiteController::class, 'toggleArchive'])->name('websites.archive');
    Route::post('/websites/fetch-metadata', [WebsiteController::class, 'fetchMetadata'])->name('websites.fetch-metadata');
});

require __DIR__.'/auth.php';
