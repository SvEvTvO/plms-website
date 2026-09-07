<?php

// Paksa PHP untuk menampilkan semua level error ke layar
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {
    // 1. Suntikkan tanda Vercel
    putenv('IS_VERCEL=true');
    $_ENV['IS_VERCEL'] = true;

    // 2. Buat direktori sementara
    $storageDirs = [
        '/tmp/storage/framework/views',
        '/tmp/storage/framework/cache',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/logs',
    ];

    foreach ($storageDirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    // 3. Jalankan Laravel
    require __DIR__ . '/../public/index.php';

} catch (\Throwable $e) {
    // Jika PHP mati/crash, tangkap dan cetak error aslinya!
    echo "<div style='font-family: sans-serif; padding: 20px;'>";
    echo "<h2 style='color: #dc2626;'>🚨 Vercel Fatal Error Caught!</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Lokasi:</strong> " . $e->getFile() . " (Baris " . $e->getLine() . ")</p>";
    echo "<div style='background: #f1f5f9; padding: 15px; border-radius: 8px; overflow-x: auto;'>";
    echo "<pre style='font-size: 12px; color: #334155;'>" . $e->getTraceAsString() . "</pre>";
    echo "</div></div>";
}
