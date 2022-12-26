<?php

declare(strict_types=1);

namespace Modules\Communication\Enums;

use App\Enums\BaseEnum;
use App\Models\Action;
use Modules\Communication\Models\DatabaseNotification;
use Modules\Role\Contracts\PermissionEnum;

final class NotificationPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_NOTIFICATION = 'create notification';

    /**
     * @var string
     */
    public const VIEW_NOTIFICATION = 'view notification';

    /**
     * @var string
     */
    public const EDIT_NOTIFICATION = 'edit notification';

    /**
     * @var string
     */
    public const DELETE_NOTIFICATION = 'delete notification';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_NOTIFICATION => Action::NAMES['create'],
            self::VIEW_NOTIFICATION => Action::NAMES['view'],
            self::EDIT_NOTIFICATION => Action::NAMES['edit'],
            self::DELETE_NOTIFICATION => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return DatabaseNotification::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Communication';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_NOTIFICATION => 'Create notification',
            self::VIEW_NOTIFICATION => 'View notification',
            self::EDIT_NOTIFICATION => 'Edit notification',
            self::DELETE_NOTIFICATION => 'Delete notification',
        ];
    }
}
