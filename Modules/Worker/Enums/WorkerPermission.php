<?php

namespace Modules\Worker\Enums;

use Modules\Role\Contracts\PermissionEnum;

final class WorkerPermission implements PermissionEnum
{
    public const CREATE_WORKERS = 'create workers';
    public const VIEW_WORKERS = 'view workers';
    public const EDIT_WORKERS = 'edit workers';
    public const DELETE_WORKERS = 'delete workers';

    public static function module(): string
    {
        return 'Workers';
    }

    public static function descriptions() : array
    {
        return [
            self::CREATE_WORKERS => 'Create workers',
            self::VIEW_WORKERS => 'View workers',
            self::EDIT_WORKERS => 'Update workers',
            self::DELETE_WORKERS => 'Delete workers',
        ];
    }
}
