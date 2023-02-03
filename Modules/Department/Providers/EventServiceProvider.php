<?php

declare(strict_types=1);

namespace Modules\Department\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Department\Events\DepartmentCreated;
use Modules\Department\Listeners\CreateSaleStatusesOnDepartmentCreated;

final class EventServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $listen = [
        DepartmentCreated::class => [
            CreateSaleStatusesOnDepartmentCreated::class,
        ],
    ];
}
