<?php

namespace App\Providers;

//========= Importar Paginación
use Illuminate\Pagination\Paginator;
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
        //========= Paginación de Bootstrap
        Paginator::useBootstrapFive();
    }
}
