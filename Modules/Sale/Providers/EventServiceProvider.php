<?php

declare(strict_types=1);

namespace Modules\Sale\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Sale\Observers\SaleStatusObserver;
use Modules\Sale\Models\SaleStatus;

final class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        SaleStatus::observe(SaleStatusObserver::class);
    }
}
