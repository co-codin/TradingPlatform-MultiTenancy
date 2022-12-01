<?php

declare(strict_types=1);

namespace Modules\User\Enums;

use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;

final class UserPermission extends Enum implements PermissionEnum
{
    public const CREATE_USERS = 'create users';
    public const VIEW_USERS = 'view users';
    public const EDIT_USERS = 'edit users';
    public const DELETE_USERS = 'delete users';
    public const BAN_USERS = 'ban users';
    public const BAN_CUSTOMERS = 'ban customers';
    public const VIEW_DEPARTMENT_USERS = 'view department users';

    public static function descriptions(): array
    {
        return [
            self::CREATE_USERS => 'Create users',
            self::VIEW_USERS => 'View users',
            self::EDIT_USERS => 'Edit users',
            self::DELETE_USERS => 'Delete users',
            self::BAN_USERS => 'Ban users',
            self::BAN_CUSTOMERS => 'Ban customers',
            self::VIEW_DEPARTMENT_USERS => 'View department users',
        ];
    }

    public static function module(): string
    {
        return 'User';
    }
}
