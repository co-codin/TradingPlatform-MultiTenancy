<?php

namespace Modules\Campaign\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\Campaign\Http\Controllers';

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

    public function map()
    {
        $this->mapAdminRoutes();
    }


    protected function mapAdminRoutes()
    {
        Route::middleware(['api', 'auth:sanctum'])
            ->as('admin.')
            ->prefix('admin')
            ->group(module_path('Campaign', '/Routes/admin.php'));
    }
}
