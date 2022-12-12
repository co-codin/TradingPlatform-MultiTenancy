<?php

declare(strict_types=1);

namespace Modules\Customer\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
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
        return new Content(
            view: 'customer::emails.user-assigned-to-customer',
            with: [
                'brandName' => $this->customer->getBrand()?->name,
                'brandLogo' => $this->customer->getBrand()?->logo,
                'userFirstName' => $this->user->first_name,
                'userLastName' => $this->user->last_name,
                'customerFirstName' => $this->customer->first_name,
                'customerLastName' => $this->customer->last_name,
                'customerEmail' => $this->customer->email,
            ],
        );
    }
}
