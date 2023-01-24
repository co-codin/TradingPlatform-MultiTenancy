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

    public static function module(): string
    {
        // TODO: Implement module() method.
    }

    public static function model(): string
    {
        // TODO: Implement model() method.
    }

    public static function descriptions(): array
    {
        // TODO: Implement descriptions() method.
    }
}
