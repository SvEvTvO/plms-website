<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Debugging: log request headers, cookies, CSRF token and current session id
        try {
            $hdrs = function_exists('getallheaders') ? getallheaders() : [];
        } catch (\Throwable $e) {
            $hdrs = [];
        }
        error_log('[AUTH-POST] headers: ' . json_encode($hdrs));
        error_log('[AUTH-POST] cookies: ' . json_encode($_COOKIE ?? []));
        try {
            error_log('[AUTH-POST] csrf_token: ' . csrf_token());
        } catch (\Throwable $e) {
            error_log('[AUTH-POST] csrf_token: (unavailable)');
        }
        try {
            error_log('[AUTH-POST] session_id_before: ' . session()->getId());
        } catch (\Throwable $e) {
            error_log('[AUTH-POST] session_id_before: (unavailable)');
        }
        try {
            error_log('[AUTH-POST] session_config: ' . json_encode([
                'lifetime' => config('session.lifetime'),
                'secure' => config('session.secure'),
                'same_site' => config('session.same_site'),
                'expire_on_close' => config('session.expire_on_close'),
            ], JSON_UNESCAPED_SLASHES));
        } catch (\Throwable $e) {
            error_log('[AUTH-POST] session_config: (unavailable)');
        }

        $request->authenticate();

        $request->session()->regenerate();

        try {
            error_log('[AUTH-POST] session_id_after: ' . session()->getId());
            error_log('[AUTH-POST] session_data: ' . json_encode(session()->all()));
        } catch (\Throwable $e) {
            error_log('[AUTH-POST] session logging failed');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
