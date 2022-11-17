<?php

namespace Modules\Brand\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Brand\Events\BrandCreated;
use Modules\Brand\Listeners\CreateBrandSchemaOnBrandCreated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BrandCreated::class => [
            CreateBrandSchemaOnBrandCreated::class,
        ],
    ];
}
