<?php

namespace Modules\Customer\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Modules\Brand\Models\Brand;
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
        private readonly Brand $brand,
        public readonly int $customerId,
        public readonly ?string $password = null,
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
        $this->brand->makeCurrent();
        $customer = Customer::query()->find($this->customerId);

        return new Content(
            view: 'customer::emails.welcome',
            with: [
                'brandName' => $this->brand->name,
                'brandLogo' => $this->brand->logo,
                'userFirstName' => $customer->first_name,
                'userLastName' => $customer->last_name,
                'userEmail' => $customer->email,
                'userPassword' => $this->password,
            ],
        );
    }
}
