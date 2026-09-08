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
        '/bootstrap/cache'
    ];

    foreach ($dirs as $dir) {
        if (!is_dir($tmp . $dir)) {
            mkdir($tmp . $dir, 0777, true);
        }
    }

    // 2. Kumpulan Environment Wajib (Gabungan Anti-Blank & Anti-Amnesia)
    $env = [
        // Jalur Vercel agar tidak kena file system Read-Only
        'LARAVEL_STORAGE_PATH'   => $tmp,
        'VIEW_COMPILED_PATH'     => $tmp . '/framework/views',
        'APP_SERVICES_CACHE'     => $tmp . '/bootstrap/cache/services.php',
        'APP_PACKAGES_CACHE'     => $tmp . '/bootstrap/cache/packages.php',
        'APP_CONFIG_CACHE'       => $tmp . '/bootstrap/cache/config.php',
        'APP_ROUTES_CACHE'       => $tmp . '/bootstrap/cache/routes.php',
        'APP_EVENTS_CACHE'       => $tmp . '/bootstrap/cache/events.php',

        // INI YANG BIKIN ERROR (KITA MASUKKAN LAGI)
        'APP_MAINTENANCE_DRIVER' => 'array',

        // Kunci Sesi & Log
        'SESSION_DRIVER'         => 'cookie', // Paling aman di Vercel, anti-mental
        'SESSION_SECURE_COOKIE'  => 'true',
        'CACHE_STORE'            => 'array',
        'CACHE_DRIVER'           => 'array',
        'LOG_CHANNEL'            => 'stderr',

        // Perbaikan Login Bcrypt
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

} catch (\Throwable $e) {
    // Skrip X-Ray
    echo "<div style='font-family: sans-serif; padding: 20px; background: #ffe4e6; color: #9f1239; border-radius: 8px;'>";
    echo "<h2>🚨 Vercel PHP Crash Log</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Lokasi:</strong> " . $e->getFile() . " (Baris " . $e->getLine() . ")</p>";
    echo "<pre style='background: #fff; padding: 15px; border-radius: 5px; overflow-x: auto; color: #333;'>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}
