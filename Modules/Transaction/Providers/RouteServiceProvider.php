<?php

namespace Modules\Transaction\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map(): void
    {
        $this->mapCustomerRoutes();
        $this->mapAdminRoutes();
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAdminRoutes(): void
    {
        Route::prefix('admin')
            ->as('admin.')
            ->middleware(['api', 'auth:api', 'tenant'])
            ->group(module_path('Transaction', '/Routes/admin.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapCustomerRoutes(): void
    {
        Route::prefix('customer')
            ->as('customer.')
            ->group(module_path('Transaction', '/Routes/customer.php'));
    }
}
