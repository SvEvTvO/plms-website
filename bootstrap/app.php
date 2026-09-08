<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // --- TAMBAHKAN BARIS INI UNTUK MEMPERBAIKI CSS BLANK (HTTPS) ---
        $middleware->trustProxies(at: '*');

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// --- JALUR PAKSA VERCEL STORAGE ---
if (getenv('IS_VERCEL') || isset($_ENV['IS_VERCEL'])) {
    $app->useStoragePath('/tmp/storage');
}
// ----------------------------------

return $app;
