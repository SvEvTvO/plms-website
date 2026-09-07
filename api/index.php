<?php

// 1. Suntikkan tanda paksa bahwa ini berjalan di Vercel
putenv('IS_VERCEL=true');
$_ENV['IS_VERCEL'] = true;

// 2. Buat seluruh struktur folder sementara yang dibutuhkan Laravel
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 3. Jalankan aplikasi utama
require __DIR__ . '/../public/index.php';
