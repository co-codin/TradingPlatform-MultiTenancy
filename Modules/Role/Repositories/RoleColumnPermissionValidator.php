<?php

declare(strict_types=1);

namespace Modules\Role\Repositories;

use App\Repositories\BaseColumnPermissionValidator;
use Modules\Role\Enums\RolePermission;

final class RoleColumnPermissionValidator extends BaseColumnPermissionValidator
{
    /**
     * {@inheritDoc}
     */
    protected function getRequestFieldName(): string
    {
        return 'roles';
    }

    /**
     * {@inheritDoc}
     */
    protected function getBasePermissionName(): string
    {
        return RolePermission::VIEW_ROLES;
    }
}
