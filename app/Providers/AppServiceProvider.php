<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    // configurar bootstrap para la paginacion
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
