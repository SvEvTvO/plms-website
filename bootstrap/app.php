<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

        // --- DEBUG: tangkap exception 419 (CSRF mismatch) di route /login ---
        $exceptions->render(function (\Throwable $e, $request) {

            if ($request->is('login')) {
                $isCsrf419 = $e instanceof HttpException && $e->getStatusCode() === 419;

                error_log('[EXC-DEBUG] class=' . get_class($e)
                    . ' | message=' . $e->getMessage()
                    . ' | is_csrf_419=' . ($isCsrf419 ? 'yes' : 'no'));

                if ($isCsrf419) {
                    try {
                        error_log('[CSRF-MISMATCH] ' . json_encode([
                            'session_id'          => session()->getId(),
                            'session_token'       => session()->token(),
                            'posted_token'        => $request->input('_token'),
                            'header_token'        => $request->header('X-CSRF-TOKEN'),
                            'cookie_header'       => $request->header('Cookie'),
                            'raw_cookies'         => $_COOKIE ?? [],
                            'session_driver'      => config('session.driver'),
                            'session_domain'      => config('session.domain'),
                            'session_secure'      => config('session.secure'),
                            'session_same_site'   => config('session.same_site'),
                            'session_cookie_name' => config('session.cookie'),
                            'is_secure_request'   => $request->isSecure(),
                            'host'                => $request->getHost(),
                        ], JSON_UNESCAPED_SLASHES));
                    } catch (\Throwable $inner) {
                        error_log('[CSRF-MISMATCH] logging failed: ' . $inner->getMessage());
                    }
                }
            }

            // return null supaya Laravel tetap render halaman error seperti biasa
            return null;
        });

    })->create();

// --- JALUR PAKSA VERCEL STORAGE ---
if (getenv('IS_VERCEL') || isset($_ENV['IS_VERCEL'])) {
    $app->useStoragePath('/tmp/storage');
}
// ----------------------------------

return $app;
