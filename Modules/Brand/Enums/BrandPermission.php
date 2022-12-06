<?php

namespace Modules\Brand\Enums;

use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;

class BrandPermission extends Enum implements PermissionEnum
{
    const CREATE_BRANDS = 'create brands';
    const VIEW_BRANDS = 'view brands';
    const EDIT_BRANDS = 'edit brands';
    const DELETE_BRANDS = 'delete brands';

    public static function module(): string
    {
        return 'Brand';
    }

    public static function descriptions() : array
    {
        return [
            static::CREATE_BRANDS => 'Create brands',
            static::VIEW_BRANDS => 'View brands',
            static::EDIT_BRANDS => 'Update brands',
            static::DELETE_BRANDS => 'Delete brands',
        ];
    }
}
