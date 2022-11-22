<?php

declare(strict_types=1);

namespace Modules\Brand\Events;

use App\Contracts\HasTenantDBConnection;
use App\Contracts\TenantEventCreated;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BrandEvent implements TenantEventCreated
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    final public function __construct(public HasTenantDBConnection $tenant)
    {}

    /**
     * {@inheritDoc}
     */
    public function getTenantDBName(): string
    {
        return $this->tenant->slug;
    }

    /**
     * {@inheritDoc}
     */
    public function getTenantSchemaName(): string
    {
        return $this->tenant->getTenantSchemaName();
    }
}
