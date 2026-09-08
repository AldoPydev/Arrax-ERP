<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // RUTAS PUBLICAS
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        
        // RUTAS ADMINISTRACIÓN
        then: function () {
            //Route::middleware(['web', 'auth', 'role:admin']) // Protegidas por sesión y rol
            Route::middleware(['web']) // Protegidas por sesión y rol
                ->prefix('admin')                            // Todas empiezan con misitio.com/admin/...
                ->name('admin.')                             // Sus nombres empiezan con admin. (ej. admin.dashboard)
                ->group(base_path('routes/admin.php'));      // Archivo que contiene las rutas
        },

    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
