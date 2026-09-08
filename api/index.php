<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

try {
    $tmp = '/tmp/laravel';
    $dirs = ['/framework/sessions', '/framework/cache/data', '/framework/views', '/logs'];

    foreach ($dirs as $dir) {
        if (!is_dir($tmp . $dir)) { mkdir($tmp . $dir, 0777, true); }
    }

    $env = [
        'APP_SERVICES_CACHE'     => $tmp . '/services.php',
        'APP_PACKAGES_CACHE'     => $tmp . '/packages.php',
        'APP_CONFIG_CACHE'       => $tmp . '/config.php',
        'APP_ROUTES_CACHE'       => $tmp . '/routes.php',
        'APP_EVENTS_CACHE'       => $tmp . '/events.php',
        'LARAVEL_STORAGE_PATH'   => $tmp,
        'VIEW_COMPILED_PATH'     => $tmp . '/framework/views',

        // --- KUNCI FINAL: GUNAKAN DATABASE ---
        'SESSION_DRIVER'         => 'database',
        // -------------------------------------

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

    require __DIR__ . '/../public/index.php';

} catch (\Throwable $e) {
    echo "<h2 style='color:red;'>🚨 Fatal Error Caught!</h2>";
    echo "<p><strong>" . $e->getMessage() . "</strong></p>";
}
