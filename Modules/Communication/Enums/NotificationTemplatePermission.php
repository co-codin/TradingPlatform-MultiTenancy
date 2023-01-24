<?php

declare(strict_types=1);

namespace Modules\Communication\Enums;

use App\Enums\BaseEnum;
use App\Models\Action;
use Modules\Communication\Models\NotificationTemplate;
use Modules\Role\Contracts\PermissionEnum;

final class NotificationTemplatePermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_NOTIFICATION_TEMPLATE = 'create notification template';

    /**
     * @var string
     */
    public const VIEW_NOTIFICATION_TEMPLATE = 'view notification template';

    /**
     * @var string
     */
    public const EDIT_NOTIFICATION_TEMPLATE = 'edit notification template';

    /**
     * @var string
     */
    public const DELETE_NOTIFICATION_TEMPLATE = 'delete notification template';

    /**
     * @var string
     */
    public const SEND_NOTIFICATION_TEMPLATE = 'send notification template';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_NOTIFICATION_TEMPLATE => Action::NAMES['create'],
            self::VIEW_NOTIFICATION_TEMPLATE => Action::NAMES['view'],
            self::EDIT_NOTIFICATION_TEMPLATE => Action::NAMES['edit'],
            self::DELETE_NOTIFICATION_TEMPLATE => Action::NAMES['delete'],
            self::SEND_NOTIFICATION_TEMPLATE => Action::NAMES['send'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return NotificationTemplate::class;
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
            self::CREATE_NOTIFICATION_TEMPLATE => 'Create notification template',
            self::VIEW_NOTIFICATION_TEMPLATE => 'View notification template',
            self::EDIT_NOTIFICATION_TEMPLATE => 'Edit notification template',
            self::DELETE_NOTIFICATION_TEMPLATE => 'Delete notification template',
            self::SEND_NOTIFICATION_TEMPLATE => 'Send notification template',
        ];
    }
}
