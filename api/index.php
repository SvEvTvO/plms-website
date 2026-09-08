<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {
    // 1. Buat direktori sementara (Vercel Read-Only bypass)
    $tmp = '/tmp/laravel';
    $dirs = ['/framework/sessions', '/framework/cache/data', '/framework/views', '/logs'];

    foreach ($dirs as $dir) {
        if (!is_dir($tmp . $dir)) {
            mkdir($tmp . $dir, 0777, true);
        }
    }

    // 2. Suntikkan variabel dengan Cookie Session (Bypass Supabase Pooler Bug)
    $env = [
        // Alihkan semua cache ke /tmp agar cache lokal dari GitHub tidak terbaca
        'APP_SERVICES_CACHE'     => $tmp . '/services.php',
        'APP_PACKAGES_CACHE'     => $tmp . '/packages.php',
        'APP_CONFIG_CACHE'       => $tmp . '/config.php',
        'APP_ROUTES_CACHE'       => $tmp . '/routes.php',
        'APP_EVENTS_CACHE'       => $tmp . '/events.php',

        'LARAVEL_STORAGE_PATH'   => $tmp,
        'VIEW_COMPILED_PATH'     => $tmp . '/framework/views',

        // KUNCI ANTI-AMNESIA: Gunakan Cookie, hindari Supabase untuk Session
        'SESSION_DRIVER'         => 'cookie',
        'SESSION_DOMAIN'         => '', // Kosongkan agar otomatis menyesuaikan domain
        'SESSION_SECURE_COOKIE'  => 'true',

        'CACHE_STORE'            => 'array',
        'CACHE_DRIVER'           => 'array',
        'LOG_CHANNEL'            => 'stderr',
        'APP_MAINTENANCE_DRIVER' => 'array',
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
    // Skrip X-Ray: Tangkap error fatal agar tidak pernah layar putih lagi
    echo "<div style='font-family: sans-serif; padding: 20px; background: #ffe4e6; color: #9f1239; border-radius: 8px;'>";
    echo "<h2>🚨 Vercel PHP Crash Log</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Lokasi:</strong> " . $e->getFile() . " (Baris " . $e->getLine() . ")</p>";
    echo "<pre style='background: #fff; padding: 15px; border-radius: 5px; overflow-x: auto; color: #333;'>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}
