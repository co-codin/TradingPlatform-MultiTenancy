<?php

declare(strict_types=1);

namespace Modules\Geo\Enums;

use App\Enums\BaseEnum;
use App\Models\Action;
use Modules\Geo\Models\Country;
use Modules\Role\Contracts\PermissionEnum;

final class CountryPermission extends BaseEnum implements PermissionEnum
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
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_COUNTRIES => Action::NAMES['create'],
            self::VIEW_COUNTRIES => Action::NAMES['view'],
            self::EDIT_COUNTRIES => Action::NAMES['edit'],
            self::DELETE_COUNTRIES => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Country::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Countries';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_COUNTRIES => 'Create countries',
            self::VIEW_COUNTRIES => 'View countries',
            self::EDIT_COUNTRIES => 'Edit countries',
            self::DELETE_COUNTRIES => 'Delete countries',
        ];
    }
}
