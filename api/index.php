<?php

// 1. Buat folder penyimpanan sementara khusus Vercel
$tmp = '/tmp/laravel';
$dirs = ['/framework/sessions', '/framework/cache/data', '/framework/views', '/logs'];

foreach ($dirs as $dir) {
    if (!is_dir($tmp . $dir)) {
        mkdir($tmp . $dir, 0777, true);
    }
}

// 2. Beritahu Laravel untuk menggunakan folder sementara tersebut
$_ENV['LARAVEL_STORAGE_PATH'] = $tmp;
$_SERVER['LARAVEL_STORAGE_PATH'] = $tmp;
putenv("LARAVEL_STORAGE_PATH={$tmp}");

$_ENV['VIEW_COMPILED_PATH'] = $tmp . '/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = $tmp . '/framework/views';
putenv("VIEW_COMPILED_PATH={$tmp}/framework/views");

// 3. Nyalakan Laravel
require __DIR__ . '/../public/index.php';
