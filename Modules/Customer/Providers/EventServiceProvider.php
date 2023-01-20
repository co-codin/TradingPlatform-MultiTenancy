<?php

declare(strict_types=1);

namespace Modules\Customer\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Customer\Events\CustomerEdited;
use Modules\Customer\Events\CustomerSaving;
use Modules\Customer\Events\CustomerStored;
use Modules\Customer\Listeners\CheckCustomerConversionListener;
use Modules\Customer\Listeners\CheckCustomerRetentionListener;
use Modules\Customer\Listeners\SendUserAssignedToCustomerEmailOnCustomerEdited;
use Modules\Customer\Listeners\SendUserAssignedToCustomerEmailOnCustomerStored;
use Modules\Customer\Listeners\SendWelcomeCustomerEmail;
use Modules\Customer\Models\Customer;
use Modules\Customer\Observers\CustomerObserver;

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
        CustomerStored::class => [
            SendWelcomeCustomerEmail::class,
            SendUserAssignedToCustomerEmailOnCustomerStored::class,
        ],
        CustomerEdited::class => [
            SendUserAssignedToCustomerEmailOnCustomerEdited::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        Customer::observe(CustomerObserver::class);
    }
}
