<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

// 1. SUNTIK PAKSA PENGATURAN SERVERLESS KE JANTUNG LARAVEL
$serverlessEnv = [
    'IS_VERCEL' => 'true',
    'LOG_CHANNEL' => 'stderr',
    'SESSION_DRIVER' => 'cookie',
    'CACHE_DRIVER' => 'array',
    'CACHE_STORE' => 'array',
    'VIEW_COMPILED_PATH' => '/tmp/views',
];

foreach ($serverlessEnv as $key => $value) {
    putenv("{$key}={$value}");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

// 2. BUAT FOLDER SEMENTARA
$storageDirs = [
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/views'
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// 3. JALANKAN APLIKASI
require __DIR__ . '/../public/index.php';
