<?php

// Tampilkan error jika terjadi crash
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Buat struktur direktori untuk Vercel
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

// Beri tanda bahwa aplikasi berjalan di Vercel
putenv('IS_VERCEL=true');
$_ENV['IS_VERCEL'] = true;

require __DIR__ . '/../public/index.php';
