<?php

namespace Modules\Desk\Enums;

use Modules\Role\Contracts\PermissionEnum;

class DeskPermission implements PermissionEnum
{
    const CREATE_DESKS = 'create desks';
    const VIEW_DESKS = 'view desks';
    const EDIT_DESKS = 'edit desks';
    const DELETE_DESKS = 'delete desks';

    public static function module(): string
    {
        return 'Desks';
    }

    public static function descriptions() : array
    {
        return [
            static::CREATE_DESKS => 'Create desks',
            static::VIEW_DESKS => 'View desks',
            static::EDIT_DESKS => 'Edit desks',
            static::DELETE_DESKS => 'Delete desks',
        ];
    }
}
