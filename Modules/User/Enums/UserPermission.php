<?php

namespace Modules\User\Enums;

use Modules\Role\Contracts\PermissionEnum;

final class UserPermission implements PermissionEnum
{
    const CREATE_USERS = 'create users';
    const VIEW_USERS = 'view users';
    const EDIT_USERS = 'edit users';
    const DELETE_USERS = 'delete users';

    public static function module(): string
    {
        return 'Users';
    }

    public static function descriptions() : array
    {
        return [
            self::CREATE_USERS => 'Create users',
            self::VIEW_USERS => 'View users',
            self::EDIT_USERS => 'Update users',
            self::DELETE_USERS => 'Delete users',
        ];
    }
}
