<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Enums;

use App\Enums\BaseEnum;
use App\Models\Action;
use Modules\CommunicationProvider\Models\CommunicationExtension;
use Modules\Role\Contracts\PermissionEnum;

final class CommunicationExtensionPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_TELEPHONY_EXTENSION = 'create communication extension';

    /**
     * @var string
     */
    public const VIEW_TELEPHONY_EXTENSION = 'view communication extension';

    /**
     * @var string
     */
    public const EDIT_TELEPHONY_EXTENSION = 'edit communication extension';

    /**
     * @var string
     */
    public const DELETE_TELEPHONY_EXTENSION = 'delete communication extension';

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
        return CommunicationExtension::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'CommunicationProvider';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_TELEPHONY_EXTENSION => 'Create communication extension',
            self::VIEW_TELEPHONY_EXTENSION => 'View communication extension',
            self::EDIT_TELEPHONY_EXTENSION => 'Edit communication extension',
            self::DELETE_TELEPHONY_EXTENSION => 'Delete communication extension',
        ];
    }
}
