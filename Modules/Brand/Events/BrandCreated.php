<?php

declare(strict_types=1);

namespace Modules\Brand\Events;

use App\Contracts\TenantEventCreated;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Brand\Models\Brand;

final class BrandCreated implements TenantEventCreated
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    final public function __construct(public readonly Brand $brand)
    {}

    /**
     * {@inheritDoc}
     */
    public function getTenantDBName(): string
    {
        return $this->brand->slug;
    }
}
