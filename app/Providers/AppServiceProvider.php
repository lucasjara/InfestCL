<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('public-api', function (Request $request) {
            return Limit::perMinute(max(1, (int) config('api.rate_limit', 60)))
                ->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Demasiadas solicitudes. Intenta nuevamente más tarde.',
                    ], 429, array_merge($headers, [
                        'Cache-Control' => 'no-store, max-age=0',
                        'X-Content-Type-Options' => 'nosniff',
                        'X-Frame-Options' => 'DENY',
                        'Referrer-Policy' => 'no-referrer',
                    ]));
                });
        });
    }
}
