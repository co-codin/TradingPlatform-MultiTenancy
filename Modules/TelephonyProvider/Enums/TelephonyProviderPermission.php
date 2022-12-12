<?php

declare(strict_types=1);

namespace Modules\TelephonyProvider\Enums;

use App\Enums\BaseEnum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Models\Action;
use Modules\TelephonyProvider\Models\TelephonyProvider;

final class TelephonyProviderPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_TELEPHONY_PROVIDER = 'create telephony provider';

    /**
     * @var string
     */
    public const VIEW_TELEPHONY_PROVIDER = 'view telephony provider';

    /**
     * @var string
     */
    public const EDIT_TELEPHONY_PROVIDER = 'edit telephony provider';

    /**
     * @var string
     */
    public const DELETE_TELEPHONY_PROVIDER = 'delete telephony provider';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_TELEPHONY_PROVIDER => Action::NAMES['create'],
            self::VIEW_TELEPHONY_PROVIDER => Action::NAMES['view'],
            self::EDIT_TELEPHONY_PROVIDER => Action::NAMES['edit'],
            self::DELETE_TELEPHONY_PROVIDER => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return TelephonyProvider::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'TelephonyProvider';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_TELEPHONY_PROVIDER => 'Create telephony provider',
            self::VIEW_TELEPHONY_PROVIDER => 'View telephony provider',
            self::EDIT_TELEPHONY_PROVIDER => 'Edit telephony provider',
            self::DELETE_TELEPHONY_PROVIDER => 'Delete telephony provider',
        ];
    }
}
