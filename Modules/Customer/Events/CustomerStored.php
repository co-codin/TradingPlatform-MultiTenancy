<?php

declare(strict_types=1);

namespace Modules\Customer\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\Customer\Dto\CustomerDto;
use Modules\Customer\Models\Customer;
use Spatie\Multitenancy\Models\Tenant;

final class CustomerStored implements ShouldBroadcast
{
    use Dispatchable;

    public Tenant $tenant;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public Customer $customer,
        public ?CustomerDto $dto = null,
    ) {
        $this->tenant = $this->customer->getBrand();
    }

    public function broadcastOn()
    {
//        return ['customer.'.$this->customer->id];
    }

    public function broadcastWith()
    {
//        return ['customer' => $this->customer];
    }
}
