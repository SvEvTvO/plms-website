<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {
    // 1. Buat folder sementara
    $dirs = [
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/framework/views',
        '/tmp/storage/logs',
    ];

    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    // 2. Suntikkan Variabel Bypass Cache & Storage
    $serverlessEnv = [
        // BYPASS ABSOLUTE PATH MISMATCH (Solusi dari Error "view")
        'APP_SERVICES_CACHE'     => '/tmp/services.php',
        'APP_PACKAGES_CACHE'     => '/tmp/packages.php',
        'APP_CONFIG_CACHE'       => '/tmp/config.php',
        'APP_ROUTES_CACHE'       => '/tmp/routes.php',
        'APP_EVENTS_CACHE'       => '/tmp/events.php',

        // STORAGE & DRIVERS
        'LARAVEL_STORAGE_PATH'   => '/tmp/storage',
        'APP_MAINTENANCE_DRIVER' => 'array',
        'LOG_CHANNEL'            => 'stderr',
        'SESSION_DRIVER'         => 'cookie',
        'CACHE_STORE'            => 'array',
        'CACHE_DRIVER'           => 'array',
        'VIEW_COMPILED_PATH'     => '/tmp/storage/framework/views',
    ];

    foreach ($serverlessEnv as $key => $value) {
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
        putenv("{$key}={$value}");
    }

    // 3. Nyalakan mesin Laravel dengan bersih
    require __DIR__ . '/../public/index.php';

} catch (\Throwable $e) {
    echo "<h2 style='color:red;'>🚨 Fatal Error Caught!</h2>";
    echo "<p><strong>" . $e->getMessage() . "</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
