<?php

declare(strict_types=1);

namespace Modules\Role\Enums;

use Modules\Role\Contracts\PermissionEnum;

final class PermissionPermission implements PermissionEnum
{
    /**
     * @var string
     */
    const CREATE_PERMISSIONS = 'create permissions';

    /**
     * @var string
     */
    const VIEW_PERMISSIONS = 'view permissions';

    /**
     * @var string
     */
    const EDIT_PERMISSIONS = 'edit permissions';

    /**
     * @var string
     */
    const DELETE_PERMISSIONS = 'delete permissions';

    /**
     * {@inheritDoc}
     */
    final public static function module(): string
    {
        return 'Permissions';
    }

    /**
     * {@inheritDoc}
     */
    final public static function descriptions(): array
    {
        return [
            self::CREATE_PERMISSIONS => 'Create permissions',
            self::VIEW_PERMISSIONS => 'View permissions',
            self::EDIT_PERMISSIONS => 'Update permissions',
            self::DELETE_PERMISSIONS => 'Delete permissions',
        ];
    }
}
