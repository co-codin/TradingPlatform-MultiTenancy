<?php

declare(strict_types=1);

namespace Modules\Department\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Department\Models\Department;

final class DepartmentCreated
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    final public function __construct(public readonly Department $department)
    {}
}
