<?php

namespace Modules\Worker\Enums;

use Modules\Role\Contracts\PermissionEnum;

class WorkerPermission implements PermissionEnum
{
    const CREATE_WORKERS = 'create workers';
    const VIEW_WORKERS = 'view workers';
    const EDIT_WORKERS = 'edit workers';
    const DELETE_WORKERS = 'delete workers';

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
