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
        return 'Пользователи';
    }

    public static function descriptions() : array
    {
        return [
            self::CREATE_USERS => 'Добавление пользователей',
            self::VIEW_USERS => 'Просмотр пользователей',
            self::EDIT_USERS => 'Редактирование пользователей',
            self::DELETE_USERS => 'Удаление пользователей',
        ];
    }
}
