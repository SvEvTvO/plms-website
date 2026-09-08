<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;

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

        // --- DEBUG: log detail persis saat CSRF mismatch (419) terjadi ---
        $exceptions->report(function (TokenMismatchException $e) {
            try {
                Log::error('[CSRF-MISMATCH] ' . json_encode([
                    'session_id'       => session()->getId(),
                    'session_token'    => session()->token(),
                    'posted_token'     => request()->input('_token'),
                    'header_token'     => request()->header('X-CSRF-TOKEN'),
                    'cookie_header'    => request()->header('Cookie'),
                    'raw_cookies'      => $_COOKIE ?? [],
                    'session_driver'   => config('session.driver'),
                    'session_domain'   => config('session.domain'),
                    'session_secure'   => config('session.secure'),
                    'session_same_site'=> config('session.same_site'),
                    'session_cookie_name' => config('session.cookie'),
                    'is_secure_request'=> request()->isSecure(),
                    'host'             => request()->getHost(),
                ], JSON_UNESCAPED_SLASHES));
            } catch (\Throwable $inner) {
                Log::error('[CSRF-MISMATCH] logging failed: ' . $inner->getMessage());
            }
        });

    })->create();

// --- JALUR PAKSA VERCEL STORAGE ---
if (getenv('IS_VERCEL') || isset($_ENV['IS_VERCEL'])) {
    $app->useStoragePath('/tmp/storage');
}
// ----------------------------------

return $app;
