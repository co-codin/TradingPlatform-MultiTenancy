<?php

declare(strict_types=1);

namespace Modules\Role\Enums;

use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;

final class PermissionPermission extends Enum implements PermissionEnum
{
    public const CREATE_PERMISSIONS = 'create permissions';
    public const VIEW_PERMISSIONS = 'view permissions';
    public const EDIT_PERMISSIONS = 'edit permissions';
    public const DELETE_PERMISSIONS = 'delete permissions';

    public static function descriptions(): array
    {
        return [
            self::CREATE_PERMISSIONS => 'Create permissions',
            self::VIEW_PERMISSIONS => 'View permissions',
            self::EDIT_PERMISSIONS => 'Update permissions',
            self::DELETE_PERMISSIONS => 'Delete permissions',
        ];
    }

    public static function module(): string
    {
        return 'Permissions';
    }
}
