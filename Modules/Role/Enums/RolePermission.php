<?php

namespace Modules\Role\Enums;

use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;

class RolePermission extends Enum implements PermissionEnum
{
    const CREATE_ROLES = 'create roles';
    const VIEW_ROLES = 'view roles';
    const EDIT_ROLES = 'edit roles';
    const DELETE_ROLES = 'delete roles';

    public static function module(): string
    {
        return 'Roles';
    }

    public static function descriptions() : array
    {
        return [
            static::CREATE_ROLES => 'Create roles',
            static::VIEW_ROLES => 'View roles',
            static::EDIT_ROLES => 'Update roles',
            static::DELETE_ROLES => 'Delete roles',
        ];
    }
}
