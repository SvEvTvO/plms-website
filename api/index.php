<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

// 1. Siapkan folder sementara Vercel
$tmp = '/tmp/laravel';
$dirs = [
    '/framework/sessions',
    '/framework/cache/data',
    '/framework/views',
    '/logs',
    '/bootstrap/cache'
];

foreach ($dirs as $dir) {
    if (!is_dir($tmp . $dir)) mkdir($tmp . $dir, 0777, true);
}

// 2. Suntikan Anti-Amnesia dan Anti-Freeze
$env = [
    'LARAVEL_STORAGE_PATH'   => $tmp,
    'VIEW_COMPILED_PATH'     => $tmp . '/framework/views',

    // KUNCI UTAMA: Paksa Cookie & Matikan Secure Check agar tidak diblokir Proxy Vercel
    'SESSION_DRIVER'         => 'cookie',
    'SESSION_SECURE_COOKIE'  => 'false',

    'CACHE_STORE'            => 'array',
    'CACHE_DRIVER'           => 'array',
    'APP_MAINTENANCE_DRIVER' => 'array',
    'LOG_CHANNEL'            => 'stderr',
    'HASH_DRIVER'            => 'bcrypt',
    'BCRYPT_ROUNDS'          => '12',
];

foreach ($env as $k => $v) {
    $_ENV[$k] = $v;
    $_SERVER[$k] = $v;
    putenv("{$k}={$v}");
}

// 3. Nyalakan Laravel
require __DIR__ . '/../public/index.php';
