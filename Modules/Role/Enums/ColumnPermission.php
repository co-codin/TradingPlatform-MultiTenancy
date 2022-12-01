<?php

declare(strict_types=1);

namespace Modules\Role\Enums;

use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;

final class ColumnPermission extends Enum implements PermissionEnum
{
    public const CREATE_COLUMNS = 'create columns';
    public const VIEW_COLUMNS = 'view columns';
    public const EDIT_COLUMNS = 'edit columns';
    public const DELETE_COLUMNS = 'delete columns';

    public static function descriptions(): array
    {
        return [
            self::CREATE_COLUMNS => 'Create columns',
            self::VIEW_COLUMNS => 'View columns',
            self::EDIT_COLUMNS => 'Update columns',
            self::DELETE_COLUMNS => 'Delete columns',
        ];
    }

    public static function module(): string
    {
        return 'Roles';
    }
}
