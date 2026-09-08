<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {
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

    $env = [
        'LARAVEL_STORAGE_PATH'   => $tmp,
        'VIEW_COMPILED_PATH'     => $tmp . '/framework/views',

        'APP_SERVICES_CACHE'     => $tmp . '/bootstrap/cache/services.php',
        'APP_PACKAGES_CACHE'     => $tmp . '/bootstrap/cache/packages.php',
        'APP_CONFIG_CACHE'       => $tmp . '/bootstrap/cache/config.php',
        'APP_ROUTES_CACHE'       => $tmp . '/bootstrap/cache/routes.php',
        'APP_EVENTS_CACHE'       => $tmp . '/bootstrap/cache/events.php',

        // --- KUNCI: GUNAKAN DATABASE (SUPABASE) UNTUK SESI ---
        'SESSION_DRIVER'         => 'database',
        'SESSION_SECURE_COOKIE'  => 'true',
        // ----------------------------------------------------

        'CACHE_STORE'            => 'array',
        'CACHE_DRIVER'           => 'array',
        'APP_MAINTENANCE_DRIVER' => 'file',
        'LOG_CHANNEL'            => 'stderr',
        'HASH_DRIVER'            => 'bcrypt',
    ];

    foreach ($env as $k => $v) {
        $_ENV[$k] = $v;
        $_SERVER[$k] = $v;
        putenv("{$k}={$v}");
    }

    // Normalize SESSION_DOMAIN if someone accidentally set full URL (e.g. https://...)
    if (!empty($_ENV['SESSION_DOMAIN'])) {
        $domain = $_ENV['SESSION_DOMAIN'];
        // If it looks like a URL, extract host part
        if (str_starts_with($domain, 'http://') || str_starts_with($domain, 'https://')) {
            $parts = parse_url($domain);
            if (!empty($parts['host'])) {
                $domain = $parts['host'];
            }
        }
        // Remove any trailing slash
        $domain = rtrim($domain, '/');
        // Update envs
        $_ENV['SESSION_DOMAIN'] = $domain;
        $_SERVER['SESSION_DOMAIN'] = $domain;
        putenv("SESSION_DOMAIN={$domain}");
    }

    // Temporary debug logging: enable by setting DEBUG_REQUEST_LOG=true in Vercel env
    if (getenv('DEBUG_REQUEST_LOG') === 'true') {
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $debug = [
            'method' => $_SERVER['REQUEST_METHOD'] ?? null,
            'uri' => $_SERVER['REQUEST_URI'] ?? null,
            'headers' => $headers,
            'cookies' => $_COOKIE ?? [],
            'post' => $_POST ?? [],
        ];
        error_log("[REQUEST-DEBUG] " . json_encode($debug));
    }

    require __DIR__ . '/../public/index.php';

} catch (\Throwable $e) {
    echo "<div style='font-family: sans-serif; padding: 20px; background: #ffe4e6; color: #9f1239; border-radius: 8px;'>";
    echo "<h2>🚨 Vercel PHP Crash Log</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<pre style='background: #fff; padding: 15px; border-radius: 5px; overflow-x: auto; color: #333;'>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}
