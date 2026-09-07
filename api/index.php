<?php

// 1. Skrip X-Ray: Jangan pernah biarkan layar blank putih lagi
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {
    // 2. Buat folder sementara untuk Vercel
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

    // 3. Suntikkan Variabel Wajib (TERMASUK TEMUAN COPILOT)
    $serverlessEnv = [
        'LARAVEL_STORAGE_PATH'   => '/tmp/storage',
        'APP_MAINTENANCE_DRIVER' => 'array', // Solusi dari Copilot! (array sangat aman untuk serverless)
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

    // 4. Nyalakan mesin Laravel
    require __DIR__ . '/../public/index.php';

} catch (\Throwable $e) {
    // Tangkap error jika masih ada yang lolos
    echo "<h2 style='color:red;'>🚨 Fatal Error Caught!</h2>";
    echo "<p><strong>" . $e->getMessage() . "</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
