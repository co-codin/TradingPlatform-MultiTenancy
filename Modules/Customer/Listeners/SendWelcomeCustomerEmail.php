<?php

declare(strict_types=1);

namespace Modules\Customer\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Customer\Emails\WelcomeCustomer;
use Modules\Customer\Events\CustomerStored;

final class SendWelcomeCustomerEmail
{
    /**
     * @param CustomerStored $event
     * @return void
     */
    public function handle(CustomerStored $event): void
    {
        Mail::to($event->customer->email)->send(
            new WelcomeCustomer($event->customer, password: $event->dto?->password)
        );
    }
}
