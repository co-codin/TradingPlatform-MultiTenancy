<?php

namespace Modules\User\Repositories;

use App\Repositories\BaseColumnPermissionValidator;
use Modules\User\Enums\UserPermission;

class UserColumnPermissionValidator extends BaseColumnPermissionValidator
{
    /**
     * {@inheritDoc}
     */
    protected function getRequestFieldName(): string
    {
        return 'users';
    }

    /**
     * {@inheritDoc}
     */
    protected function getBasePermissionName(): string
    {
        return UserPermission::VIEW_USERS;
    }
}
