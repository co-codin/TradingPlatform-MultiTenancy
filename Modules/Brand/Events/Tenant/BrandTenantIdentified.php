<?php

namespace Modules\Brand\Events\Tenant;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\Brand\Models\Brand;

class BrandTenantIdentified
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public Brand $tenant
    )
    {}
}
