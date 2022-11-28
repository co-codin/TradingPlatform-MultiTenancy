<?php

declare(strict_types=1);

namespace Modules\Department\Repositories;

use App\Repositories\BaseColumnPermissionValidator;
use Modules\Department\Enums\DepartmentPermission;

final class DepartmentColumnPermissionValidator extends BaseColumnPermissionValidator
{
    /**
     * {@inheritDoc}
     */
    final protected function getRequestFieldName(): string
    {
        return 'departments';
    }

    /**
     * {@inheritDoc}
     */
    final protected function getBasePermissionName(): string
    {
        return DepartmentPermission::VIEW_DEPARTMENTS;
    }
}
