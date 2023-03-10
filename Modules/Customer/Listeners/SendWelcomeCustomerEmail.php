<?php

declare(strict_types=1);

namespace Modules\Customer\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Customer\Emails\WelcomeCustomer;
use Modules\Customer\Events\CustomerStored;

final class SendWelcomeCustomerEmail
{
    /**
     * @param  CustomerStored  $event
     * @return void
     */
    public function handle(CustomerStored $event): void
    {
        Mail::to($event->customer->getEmail())->send(
            new WelcomeCustomer($event->tenant, $event->customer->id, password: $event->dto?->password)
        );
    }
}
