<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

// 1. Siapkan folder /tmp untuk Vercel
$tmp = '/tmp/laravel';
$dirs = [
    '/framework/sessions',
    '/framework/cache/data',
    '/framework/views',
    '/logs'
];

foreach ($dirs as $dir) {
    if (!is_dir($tmp . $dir)) @mkdir($tmp . $dir, 0777, true);
}

// 2. Suntikkan pengaturan Serverless (Anti-Blank & Anti-Amnesia)
$env = [
    'LARAVEL_STORAGE_PATH'   => $tmp,
    'VIEW_COMPILED_PATH'     => $tmp . '/framework/views',

    // Kunci Login: Gunakan Cookie dan matikan pengecekan proxy Vercel
    'SESSION_DRIVER'         => 'cookie',
    'SESSION_SECURE_COOKIE'  => 'false',

    // Bypass fitur yang butuh hardisk
    'CACHE_STORE'            => 'array',
    'CACHE_DRIVER'           => 'array',
    'APP_MAINTENANCE_DRIVER' => 'array', // Mencegah ArgumentCountError
    'LOG_CHANNEL'            => 'stderr',

    // Sesuaikan Hashing
    'HASH_DRIVER'            => 'bcrypt',
];

foreach ($env as $k => $v) {
    $_ENV[$k] = $v;
    $_SERVER[$k] = $v;
    putenv("{$k}={$v}");
}

// 3. Nyalakan Laravel
require __DIR__ . '/../public/index.php';
