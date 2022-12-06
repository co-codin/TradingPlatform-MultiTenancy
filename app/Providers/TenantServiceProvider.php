<?php

namespace App\Providers;

use App\Services\Tenant\DatabaseManager;
use App\Services\Tenant\Manager;
use Illuminate\Support\ServiceProvider;

class TenantServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Manager::class, function () {
            return new Manager();
        });
    }
}
