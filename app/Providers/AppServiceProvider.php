<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        if (config('app.env') === 'production' || request()->server('HTTP_X_FORWARDED_PROTO') === 'https') {
            URL::forceScheme('https');
        }

        RateLimiter::for('ai-chat', function (Request $request) {
            $ipKey = 'ai-chat:ip:' . $request->ip();
            $sessionKey = 'ai-chat:session:' . $request->session()->getId();

            return [
                Limit::perMinute(20)->by($ipKey),
                Limit::perMinute(10)->by($sessionKey),
            ];
        });
    }
}
