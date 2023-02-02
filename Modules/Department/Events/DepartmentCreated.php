<?php

declare(strict_types=1);

namespace Modules\Department\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\Department\Models\Department;
use Spatie\Multitenancy\Models\Tenant;

final class DepartmentCreated
{
    use Dispatchable;

    public Tenant $tenant;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    final public function __construct(public readonly Department $department)
    {
        $this->tenant = Tenant::current();
    }
}
