<?php

declare(strict_types=1);

namespace Modules\Currency\Enums;

use App\Enums\BaseEnum;
use App\Models\Action;
use Modules\Currency\Models\Currency;
use Modules\Geo\Models\Country;
use Modules\Role\Contracts\PermissionEnum;

final class CurrencyPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    const CREATE_CURRENCIES = 'create currencies';

    /**
     * @var string
     */
    const VIEW_CURRENCIES = 'view currencies';

    /**
     * @var string
     */
    const EDIT_CURRENCIES = 'edit currencies';

    /**
     * @var string
     */
    const DELETE_CURRENCIES = 'delete currencies';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_CURRENCIES => Action::NAMES['create'],
            self::VIEW_CURRENCIES => Action::NAMES['view'],
            self::EDIT_CURRENCIES => Action::NAMES['edit'],
            self::DELETE_CURRENCIES => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Currency::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Currency';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_CURRENCIES => 'Create currencies',
            self::VIEW_CURRENCIES => 'View currencies',
            self::EDIT_CURRENCIES => 'Edit currencies',
            self::DELETE_CURRENCIES => 'Delete currencies',
        ];
    }
}
