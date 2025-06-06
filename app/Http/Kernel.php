<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class, // Descomentar si se configura en AppServiceProvider
        \App\Http\Middleware\TrustProxies::class, // Publicado por defecto
        \Illuminate\Http\Middleware\HandleCors::class, // O \App\Http\Middleware\HandleCors::class si lo publicas
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class, // Publicado por defecto
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class, // Publicado por defecto
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class, // Publicado por defecto
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class, // Común con Jetstream/Breeze
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class, // Publicado por defecto
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // Si usas Sanctum para SPA
            'throttle:api', // Asegúrate de tener configurado el throttling
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used to assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    // En Laravel 9 y anteriores esto se llama: protected $routeMiddleware = [
    // En Laravel 10+ se llama:
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class, // Publicado por defecto con `php artisan make:auth` o Jetstream/Breeze
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class, // Para policies y gates
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // Publicado por defecto
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class, // Para confirmar contraseña
        'precognition' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class, // Laravel 10+
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    //    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class, // Para verificación de email

        // Middlewares personalizados para roles y permisos
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'permission' => \App\Http\Middleware\PermissionMiddleware::class,

        // Middlewares de Jetstream (si lo estás usando)
        // Estos alias se registran automáticamente por Jetstream si están instalados
        // 'team' => \Laravel\Jetstream\Http\Middleware\ShareTeamData::class, // Si usas --teams
        // 'verified_email_access' => \Laravel\Jetstream\Http\Middleware\EnsureEmailIsVerified::class, // Jetstream puede tener su propio alias
    ];
}