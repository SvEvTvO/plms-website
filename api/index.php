<?php

// 1. Buat folder sementara khusus Vercel
$dirs = [
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// 2. Gunakan fitur bawaan Laravel 11 untuk memindahkan folder Storage
$serverlessEnv = [
    'LARAVEL_STORAGE_PATH' => '/tmp/storage',
    'VIEW_COMPILED_PATH'   => '/tmp/storage/framework/views',
    'LOG_CHANNEL'          => 'stderr',
    'SESSION_DRIVER'       => 'cookie',
    'CACHE_STORE'          => 'array',
];

foreach ($serverlessEnv as $key => $value) {
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
    putenv("{$key}={$value}");
}

// 3. Nyalakan mesin Laravel
require __DIR__ . '/../public/index.php';
