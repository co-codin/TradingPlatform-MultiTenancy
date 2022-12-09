<?php

declare(strict_types=1);

namespace Modules\Customer\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\Customer\Dto\CustomerDto;
use Modules\Customer\Models\Customer;

final class CustomerEdited
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public Customer $customer,
        public ?CustomerDto $dto = null,
    ) {
    }
}
