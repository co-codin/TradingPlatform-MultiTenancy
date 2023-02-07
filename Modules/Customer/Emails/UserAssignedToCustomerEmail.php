<?php

declare(strict_types=1);

namespace Modules\Customer\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;

class UserAssignedToCustomerEmail extends Mailable //implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public readonly User $user,
        public readonly Customer $customer,
    ) {
        //
    }

    /**
     * Get the message content definition.
     *
     * @return Content
     */
    public function content(): Content
    {
        if ($brand = $this->customer->getBrand()) {
            $brand->makeCurrent();
            $with = [
                'brandName' => $brand->name,
                'brandLogo' => $brand->logo,
                'userFirstName' => $this->user->workerInfo?->first_name,
                'userLastName' => $this->user->workerInfo?->last_name,
            ];
        }

        return new Content(
            view: 'customer::emails.user-assigned-to-customer',
            with: [
                'brandName' => $with['brandName'] ?? null,
                'brandLogo' => $with['brandLogo'] ?? null,
                'userFirstName' => $with['userFirstName'] ?? null,
                'userLastName' => $with['userLastName'] ?? null,
                'customerFirstName' => $this->customer->first_name,
                'customerLastName' => $this->customer->last_name,
                'customerEmail' => $this->customer->email,
            ],
        );
    }
}
