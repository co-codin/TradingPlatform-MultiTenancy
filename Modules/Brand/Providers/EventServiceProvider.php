<?php

namespace Modules\Brand\Providers;

use Modules\Brand\Events\Tenant\BrandTenantIdentified;
use App\Listeners\Tenant\CreateTenantDatabase;
use App\Listeners\Tenant\RegisterTenant;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Brand\Events\BrandCreated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BrandCreated::class => [
            CreateTenantDatabase::class,
        ],
        BrandTenantIdentified::class => [
            RegisterTenant::class,
        ],
    ];
}
