<?php

namespace Modules\Brand\Providers;

use Modules\Brand\Events\Tenant\BrandTenantIdentified;
use App\Listeners\Tenant\RegisterTenant;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BrandTenantIdentified::class => [
            RegisterTenant::class,
        ],
    ];
}
