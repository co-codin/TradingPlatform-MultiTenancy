<?php

namespace Modules\Customer\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Modules\Customer\Models\Customer;

class WelcomeCustomer extends Mailable implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public readonly Customer $customer,
        public readonly ?string $password = null,
    )
    {
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
            view: 'customer::emails.welcome',
            with: [
                'brandName' => $this->customer->getBrand()?->name,
                'brandLogo' => $this->customer->getBrand()?->logo,
                'userFirstName' => $this->customer->first_name,
                'userLastName' => $this->customer->last_name,
                'userEmail' => $this->customer->email,
                'userPassword' => ! Hash::needsRehash($this->password) ? $this->password : null,
            ],
        );
    }
}
