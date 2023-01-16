<?php

declare(strict_types=1);

namespace Modules\Customer\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

final class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\Customer\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapAdminRoutes();
        $this->mapApiRoutes();
        $this->mapAffiliateApiRoutes();
    }

    protected function mapAffiliateApiRoutes(): void
    {
        Route::prefix('affiliate')
            ->as('affiliate.')
            ->group(module_path('Customer', '/Routes/affiliate.php'));
    }

    /**
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('customer')->as('customer.')->group(module_path('Customer', '/Routes/customer.php'));
    }

    /**
     * @return void
     */
    protected function mapAdminRoutes(): void
    {
        Route::as('admin.')
            ->prefix('admin')
            ->group(module_path('Customer', '/Routes/admin.php'));
    }
}
