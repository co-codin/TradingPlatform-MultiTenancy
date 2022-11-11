<?php

namespace Modules\Geo\Enums;

use Modules\Role\Contracts\PermissionEnum;

class CountryPermission implements PermissionEnum
{
    /**
     * @var string
     */
    const STORE_COUNTRIES = 'store countries';

    /**
     * @var string
     */
    const VIEW_COUNTRIES = 'view countries';

    /**
     * @var string
     */
    const UPDATE_COUNTRIES = 'edit countries';

    /**
     * @var string
     */
    const DELETE_COUNTRIES = 'delete countries';

    /**
     * @inheritDoc
     */
    public static function module(): string
    {
        return 'Countries';
    }

    /**
     * @inheritDoc
     */
    public static function descriptions(): array
    {
        return [
            static::STORE_COUNTRIES => 'store countries',
            static::VIEW_COUNTRIES => 'View countries',
            static::UPDATE_COUNTRIES => 'Update countries',
            static::DELETE_COUNTRIES => 'Delete countries',
        ];
    }
}
