<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Keeps utf8mb4 indexes within 767 bytes on MariaDB setups without large index prefixes.
        Schema::defaultStringLength(191);

        RateLimiter::for('inquiries', fn (Request $request) => [
            Limit::perMinute(3)->by('inquiry-minute:'.$request->ip()),
            Limit::perDay(20)->by('inquiry-day:'.$request->ip()),
        ]);
    }
}
