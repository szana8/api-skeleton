<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

final class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for(
            name: 'api',
            callback: static fn (Request $request) => Limit::perMinute(
                maxAttempts: 60,
            )->by(
                key: strval($request->user()?->id ?: $request->ip()),
            ),
        );

        $this->routes(function (): void {
            Route::middleware('api')->prefix('api')->group(
                base_path('routes/api/routes.php')
            );
        });
    }
}
