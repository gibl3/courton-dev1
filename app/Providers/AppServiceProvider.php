<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request; // <-- Correct import
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
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(3, 5)
                ->by($request->input('email') . '|' . $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        $retryAfter = $headers['Retry-After'],
                        'message' => 'Too many login attempts',
                        'retry_after' => $retryAfter,
                        'available_in' => $retryAfter
                    ], 429, $headers);
                });
        });
    }
}
