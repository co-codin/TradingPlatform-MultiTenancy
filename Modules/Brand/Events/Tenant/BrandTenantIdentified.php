<?php

declare(strict_types=1);

namespace Modules\Brand\Events\Tenant;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Brand\Models\Brand;

final class BrandTenantIdentified
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public Brand $tenant
    ) {
    }
}
