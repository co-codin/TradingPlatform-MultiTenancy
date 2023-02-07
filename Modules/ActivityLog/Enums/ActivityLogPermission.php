<?php

declare(strict_types=1);

namespace Modules\ActivityLog\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\ActivityLog\Models\ActivityLog;

final class ActivityLogPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const VIEW_ACTIVITY_LOG = 'view activity log';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::VIEW_ACTIVITY_LOG => Action::NAMES['view'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return ActivityLog::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::VIEW_ACTIVITY_LOG => 'View activity log',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'ActivityLog';
    }
}
