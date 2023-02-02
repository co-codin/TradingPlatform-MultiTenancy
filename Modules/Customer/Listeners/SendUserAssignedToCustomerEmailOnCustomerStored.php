<?php

declare(strict_types=1);

namespace Modules\Customer\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Customer\Emails\UserAssignedToCustomerEmail;
use Modules\Customer\Events\CustomerStored;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;

final class SendUserAssignedToCustomerEmailOnCustomerStored
{
    /**
     * @param  CustomerStored  $event
     * @return void
     */
    public function handle(CustomerStored $event): void
    {
        $userIds = collect($event->dto?->toArray())
            ->only(Customer::WORKER_COLUMNS_FOR_EMAILING)
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            Mail::to($user->getEmail())->send(
                new UserAssignedToCustomerEmail($user, $event->customer),
            );
        }
    }
}
