<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

// 1. HANCURKAN CACHE LOKAL YANG NYANGKUT DI VERCEL
$cacheFiles = [
    __DIR__ . '/../bootstrap/cache/config.php',
    __DIR__ . '/../bootstrap/cache/routes.php',
    __DIR__ . '/../bootstrap/cache/services.php',
    __DIR__ . '/../bootstrap/cache/packages.php',
    __DIR__ . '/../bootstrap/cache/events.php',
];

foreach ($cacheFiles as $file) {
    if (file_exists($file)) {
        @unlink($file); // Hapus file cache yang mengunci sistem
    }
}

// 2. BUAT FOLDER PENYIMPANAN SEMENTARA
$tmp = '/tmp/laravel';
$dirs = ['/framework/sessions', '/framework/cache/data', '/framework/views', '/logs'];

foreach ($dirs as $dir) {
    if (!is_dir($tmp . $dir)) {
        mkdir($tmp . $dir, 0777, true);
    }
}

// 3. PAKSA PENGATURAN SERVERLESS (ANTI-AMNESIA)
$env = [
    'LARAVEL_STORAGE_PATH'   => $tmp,
    'SESSION_DRIVER'         => 'cookie', // Sangat aman karena disimpan di browser, bukan di Vercel
    'SESSION_SECURE_COOKIE'  => 'true',
    'CACHE_STORE'            => 'array',
    'CACHE_DRIVER'           => 'array',
    'LOG_CHANNEL'            => 'stderr',
    'APP_MAINTENANCE_DRIVER' => 'array',
    'HASH_DRIVER'            => 'bcrypt',
    'BCRYPT_ROUNDS'          => '12',
];

foreach ($env as $k => $v) {
    $_ENV[$k] = $v;
    $_SERVER[$k] = $v;
    putenv("{$k}={$v}");
}

// 4. NYALAKAN LARAVEL
require __DIR__ . '/../public/index.php';
