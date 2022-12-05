<?php

declare(strict_types=1);

namespace Modules\Customer\Events;

use Modules\Customer\Models\Customer;

final class CustomerSaving
{
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public Customer $customer)
    {
    }
}
