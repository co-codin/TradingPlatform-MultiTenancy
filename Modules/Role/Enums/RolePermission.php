<?php

declare(strict_types=1);

namespace Modules\Role\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Models\Role;

final class RolePermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    const CREATE_ROLES = 'create roles';

    /**
     * @var string
     */
    const VIEW_ROLES = 'view roles';

    /**
     * @var string
     */
    const EDIT_ROLES = 'edit roles';

    /**
     * @var string
     */
    const DELETE_ROLES = 'delete roles';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_ROLES => Action::NAMES['create'],
            self::VIEW_ROLES => Action::NAMES['view'],
            self::EDIT_ROLES => Action::NAMES['edit'],
            self::DELETE_ROLES => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Role::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Roles';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_ROLES => 'Create roles',
            self::VIEW_ROLES => 'View roles',
            self::EDIT_ROLES => 'Update roles',
            self::DELETE_ROLES => 'Delete roles',
        ];
    }
}
