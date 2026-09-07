<?php

// Tampilkan error jika terjadi sesuatu
ini_set('display_errors', '1');
error_reporting(E_ALL);

// 1. KOMBINASI ULTIMATE: Bypass Cache Vercel + Suntik Driver Serverless
$serverlessConfig = [
    // Bypass semua file cache yang membeku dari proses build
    'APP_CONFIG_CACHE' => '/tmp/config.php',
    'APP_EVENTS_CACHE' => '/tmp/events.php',
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_ROUTES_CACHE' => '/tmp/routes.php',
    'APP_SERVICES_CACHE' => '/tmp/services.php',

    // Paksa Laravel menggunakan driver yang aman untuk Serverless
    'IS_VERCEL' => 'true',
    'LOG_CHANNEL' => 'stderr',
    'SESSION_DRIVER' => 'cookie',
    'CACHE_DRIVER' => 'array',
    'CACHE_STORE' => 'array',
    'VIEW_COMPILED_PATH' => '/tmp/views',
];

// Tanamkan ke jantung PHP
foreach ($serverlessConfig as $key => $value) {
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
    putenv("{$key}={$value}");
}

// 2. Buat folder sementara agar Laravel tidak panik
$tmpDirs = [
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/views'
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// 3. Nyalakan Mesin Laravel
require __DIR__ . '/../public/index.php';
