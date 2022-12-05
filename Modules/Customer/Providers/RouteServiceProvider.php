<?php

declare(strict_types=1);

namespace Modules\Customer\Providers;

use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

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
        Route::middleware(['api'])
            ->as('affiliate.')
            ->prefix('affiliate')
            ->group(module_path('Customer', '/Routes/affiliate-api.php'));
    }

    /**
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        Route::middleware(['api'])
//            ::middleware(['api', 'auth:customer']) TODO add these middlewares after customer authentication
            ->group(module_path('Customer', '/Routes/api.php'));
    }

    /**
     * @return void
     */
    protected function mapAdminRoutes(): void
    {
        Route::group([], module_path('Customer', '/Routes/admin.php'));
    }
}
