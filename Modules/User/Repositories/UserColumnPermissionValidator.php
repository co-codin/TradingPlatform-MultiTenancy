<?php

declare(strict_types=1);

namespace Modules\User\Repositories;

use App\Repositories\BaseColumnPermissionValidator;
use Modules\User\Enums\UserPermission;

final class UserColumnPermissionValidator extends BaseColumnPermissionValidator
{
    /**
     * {@inheritDoc}
     */
    final protected function getRequestFieldName(): string
    {
        return 'users';
    }

    /**
     * {@inheritDoc}
     */
    final protected function getBasePermissionName(): string
    {
        return UserPermission::VIEW_USERS;
    }
}
