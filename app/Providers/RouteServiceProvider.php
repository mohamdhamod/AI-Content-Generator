<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/en/generate';
    const ADMIN_PAGE = '/en/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // Build supported locales regex from config/languages.php
            $availableLocales = implode('|', array_keys(config('languages', [])));
            if (empty($availableLocales)) {
                $availableLocales = 'en|ar|fr|es|de';
            }

            // API routes (without locale prefix, for external apps)
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            // Optional: API routes with locale prefix (for localized responses)
            Route::prefix('{locale}/api')
                ->where(['locale' => $availableLocales])
                ->middleware(['api', 'locale'])
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            // Redirect root / to preferred locale
            Route::get('/', function () {
                $preferred = session('applocale', config('app.locale'));
                return redirect($preferred);
            });

            // Social auth routes (no locale prefix) to keep OAuth redirect URIs stable.
            Route::middleware(['web'])
                ->namespace($this->namespace)
                ->group(base_path('routes/social.php'));

            // Web routes with locale prefix
            Route::prefix('{locale}')
                ->where(['locale' => $availableLocales])
                ->middleware(['web','locale'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            // Dashboard routes under {locale}/dashboard
            Route::prefix('{locale}/dashboard')
                ->where(['locale' => $availableLocales])
                ->middleware(['web','auth','locale'])
                ->namespace($this->namespace)
                ->group(base_path('routes/dashboard.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // PDF reading loads multiple chunk images per page (and may preload).
        // The default api limiter (60/min) is too low and causes 429 responses.
        RateLimiter::for('pdf-reader', function (Request $request) {
            return Limit::perMinute(1200)->by($request->ip());
        });

        // Content generation rate limit (prevent abuse)
        RateLimiter::for('content-generation', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip())
                ->response(function ($request, $headers) {
                    return response()->json([
                        'error' => __('translation.errors.rate_limit_exceeded'),
                        'message' => __('translation.errors.too_many_requests'),
                        'retry_after' => $headers['Retry-After'] ?? 60
                    ], 429);
                });
        });

        // PDF export rate limit (resource-intensive)
        RateLimiter::for('pdf-export', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip())
                ->response(function ($request, $headers) {
                    return response()->json([
                        'error' => __('translation.errors.rate_limit_exceeded'),
                        'message' => __('translation.errors.pdf_export_limit'),
                        'retry_after' => $headers['Retry-After'] ?? 60
                    ], 429);
                });
        });

        // Social preview rate limit
        RateLimiter::for('social-preview', function (Request $request) {
            return Limit::perMinute(15)->by($request->user()?->id ?: $request->ip())
                ->response(function ($request, $headers) {
                    return response()->json([
                        'error' => __('translation.errors.rate_limit_exceeded'),
                        'message' => __('translation.errors.social_preview_limit'),
                        'retry_after' => $headers['Retry-After'] ?? 60
                    ], 429);
                });
        });

        // Login rate limit (security)
        RateLimiter::for('login', function (Request $request) {
            $email = $request->input('email');
            return Limit::perMinute(5)->by($email.$request->ip())
                ->response(function ($request, $headers) {
                    return response()->json([
                        'error' => __('translation.errors.too_many_login_attempts'),
                        'retry_after' => $headers['Retry-After'] ?? 60
                    ], 429);
                });
        });

        // API authentication rate limit
        RateLimiter::for('api-auth', function (Request $request) {
            return Limit::perHour(10)->by($request->ip())
                ->response(function ($request, $headers) {
                    return response()->json([
                        'error' => 'Too many authentication attempts',
                        'retry_after' => $headers['Retry-After'] ?? 3600
                    ], 429);
                });
        });
    }
}
