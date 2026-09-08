<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Kosongkan
    }

    public function boot(): void
    {
        // Pastikan CSS dan JS tidak terblokir
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
