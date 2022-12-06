<?php

declare(strict_types=1);

namespace Modules\Customer\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Customer\Events\CustomerSaving;
use Modules\Customer\Listeners\CheckCustomerConversionListener;
use Modules\Customer\Listeners\CheckCustomerRetentionListener;

final class EventServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $listen = [
        CustomerSaving::class => [
            CheckCustomerConversionListener::class,
            CheckCustomerRetentionListener::class,
        ],
    ];
}
