<?php

namespace Modules\Role\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;

class ActionPermission extends Enum implements PermissionEnum
{
    public const VIEW_ACTIONS = 'view actions';

    public static function actions(): array
    {
        return [
            self::VIEW_ACTIONS => Action::NAMES['view'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Action::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::VIEW_ACTIONS => 'View actions',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Roles';
    }
}
