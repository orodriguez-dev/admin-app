<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Eloquent\EloquentCustomerRepository;
//use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CustomerRepositoryInterface::class,
            EloquentCustomerRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Solo la aplicamos en entornos de producción.
        //if ($this->app->environment('production', 'staging')) {
        //    URL::forceScheme('https'); 
        //}
    }
}
