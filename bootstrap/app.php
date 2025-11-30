<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // --- Web Middleware ---
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // --- Route Middleware Aliases ---
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleHierarchy::class,
        ]);

        // Now you can use this middleware in routes like:
        // Route::get('/admin', fn() => ...)->middleware('role:admin');
        // Route::get('/moderator', fn() => ...)->middleware('role:moderator');
        // Users with higher roles (admin > moderator > user > guest) will automatically pass lower-level checks
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();