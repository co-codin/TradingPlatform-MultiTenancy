<?php

namespace Modules\Token\Enums;

use Modules\Role\Contracts\PermissionEnum;

class TokenPermission implements PermissionEnum
{
    const CREATE_TOKENS = 'create tokens';
    const VIEW_TOKENS = 'view tokens';
    const EDIT_TOKENS = 'edit tokens';
    const DELETE_TOKENS = 'delete tokens';

    public static function module(): string
    {
        return 'Tokens';
    }

    public static function descriptions() : array
    {
        return [
            static::CREATE_TOKENS => 'Create tokens',
            static::VIEW_TOKENS => 'View tokens',
            static::EDIT_TOKENS => 'Update tokens',
            static::DELETE_TOKENS => 'Delete tokens',
        ];
    }
}
