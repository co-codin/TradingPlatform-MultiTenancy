<?php

declare(strict_types=1);

namespace Modules\TelephonyProvider\Enums;

use App\Enums\BaseEnum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Models\Action;
use Modules\TelephonyProvider\Models\TelephonyExtension;

final class TelephonyExtensionPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_TELEPHONY_EXTENSION = 'create telephony extension';

    /**
     * @var string
     */
    public const VIEW_TELEPHONY_EXTENSION = 'view telephony extension';

    /**
     * @var string
     */
    public const EDIT_TELEPHONY_EXTENSION = 'edit telephony extension';

    /**
     * @var string
     */
    public const DELETE_TELEPHONY_EXTENSION = 'delete telephony extension';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_TELEPHONY_EXTENSION => Action::NAMES['create'],
            self::VIEW_TELEPHONY_EXTENSION => Action::NAMES['view'],
            self::EDIT_TELEPHONY_EXTENSION => Action::NAMES['edit'],
            self::DELETE_TELEPHONY_EXTENSION => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return TelephonyExtension::class;
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
            self::CREATE_TELEPHONY_EXTENSION => 'Create telephony extension',
            self::VIEW_TELEPHONY_EXTENSION => 'View telephony extension',
            self::EDIT_TELEPHONY_EXTENSION => 'Edit telephony extension',
            self::DELETE_TELEPHONY_EXTENSION => 'Delete telephony extension',
        ];
    }
}
