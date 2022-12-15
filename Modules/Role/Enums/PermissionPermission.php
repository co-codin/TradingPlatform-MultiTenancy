<?php

declare(strict_types=1);

namespace Modules\Role\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Models\Permission;

final class PermissionPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_PERMISSIONS = 'create permissions';

    /**
     * @var string
     */
    public const VIEW_PERMISSIONS = 'view permissions';

    /**
     * @var string
     */
    public const EDIT_PERMISSIONS = 'edit permissions';

    /**
     * @var string
     */
    public const DELETE_PERMISSIONS = 'delete permissions';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_PERMISSIONS => Action::NAMES['create'],
            self::VIEW_PERMISSIONS => Action::NAMES['view'],
            self::EDIT_PERMISSIONS => Action::NAMES['edit'],
            self::DELETE_PERMISSIONS => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Permission::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_PERMISSIONS => 'Create permissions',
            self::VIEW_PERMISSIONS => 'View permissions',
            self::EDIT_PERMISSIONS => 'Update permissions',
            self::DELETE_PERMISSIONS => 'Delete permissions',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Permissions';
    }
}
