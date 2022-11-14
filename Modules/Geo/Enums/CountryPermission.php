<?php

namespace Modules\Geo\Enums;

use Modules\Role\Contracts\PermissionEnum;

class CountryPermission implements PermissionEnum
{
    /**
     * @var string
     */
    const CREATE_COUNTRIES = 'create countries';

    /**
     * @var string
     */
    const VIEW_COUNTRIES = 'view countries';

    /**
     * @var string
     */
    const EDIT_COUNTRIES = 'edit countries';

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
            static::CREATE_COUNTRIES => 'Create countries',
            static::VIEW_COUNTRIES => 'View countries',
            static::EDIT_COUNTRIES => 'Update countries',
            static::DELETE_COUNTRIES => 'Delete countries',
        ];
    }
}