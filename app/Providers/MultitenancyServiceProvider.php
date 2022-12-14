<?php

namespace App\Providers;

use App\Tenant\TestingTenantManager;
use Illuminate\Support\ServiceProvider;

class MultitenancyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->environment('testing')) {
            $this->app->singleton(TestingTenantManager::class, function () {
                return new TestingTenantManager();
            });
        }
    }
}
