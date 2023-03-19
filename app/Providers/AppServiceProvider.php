<?php

namespace App\Providers;

use App\Interfaces\IWooCommerceService;
use App\Services\WooCommerceService;
use App\Interfaces\CategoryServiceInterface;
use App\Services\CategoryService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IWooCommerceService::class, WooCommerceService::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
    }
}
