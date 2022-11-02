<?php
declare(strict_types=1);

namespace Modules\User\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function map(): void
    {
        $this->mapAdminRoutes();
    }

    protected function mapAdminRoutes(): void
    {
        Route::middleware('api')
            ->prefix('admin')
            ->as('admin.')
            ->group(module_path('User', '/Routes/admin.php'));
    }
}
