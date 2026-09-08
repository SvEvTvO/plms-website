<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {
    // 1. Buat folder penyimpanan sementara khusus Vercel
    $tmp = '/tmp/laravel';
    $dirs = [
        '/framework/sessions',
        '/framework/cache/data',
        '/framework/views',
        '/logs',
        '/bootstrap/cache' // TAMBAHAN PENTING: Folder cache agar Laravel tidak crash
    ];

    foreach ($dirs as $dir) {
        if (!is_dir($tmp . $dir)) {
            mkdir($tmp . $dir, 0777, true);
        }
    }

    // 2. Alihkan SEMUA jalur agar Laravel aman berjalan di sistem Read-Only Vercel
    $env = [
        'LARAVEL_STORAGE_PATH'   => $tmp,
        'VIEW_COMPILED_PATH'     => $tmp . '/framework/views',

        // Bypass cache ke /tmp agar Laravel bisa menulis ulang cache yang tadi kita hapus
        'APP_SERVICES_CACHE'     => $tmp . '/bootstrap/cache/services.php',
        'APP_PACKAGES_CACHE'     => $tmp . '/bootstrap/cache/packages.php',
        'APP_CONFIG_CACHE'       => $tmp . '/bootstrap/cache/config.php',
        'APP_ROUTES_CACHE'       => $tmp . '/bootstrap/cache/routes.php',
        'APP_EVENTS_CACHE'       => $tmp . '/bootstrap/cache/events.php',
    ];

    foreach ($env as $k => $v) {
        $_ENV[$k] = $v;
        $_SERVER[$k] = $v;
        putenv("{$k}={$v}");
    }

    // 3. Nyalakan Laravel
    require __DIR__ . '/../public/index.php';

} catch (\Throwable $e) {
    // Skrip X-Ray: Tangkap error fatal agar tidak pernah layar putih lagi!
    echo "<div style='font-family: sans-serif; padding: 20px; background: #ffe4e6; color: #9f1239; border-radius: 8px;'>";
    echo "<h2>🚨 Vercel PHP Crash Log</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Lokasi:</strong> " . $e->getFile() . " (Baris " . $e->getLine() . ")</p>";
    echo "<pre style='background: #fff; padding: 15px; border-radius: 5px; overflow-x: auto; color: #333;'>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}
