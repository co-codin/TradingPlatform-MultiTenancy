<?php

declare(strict_types=1);

namespace Modules\User\Enums;

use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Models\Action;
use Modules\User\Models\User;

final class UserPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_USERS = 'create users';

    /**
     * @var string
     */
    public const VIEW_USERS = 'view users';

    /**
     * @var string
     */
    public const EDIT_USERS = 'edit users';

    /**
     * @var string
     */
    public const DELETE_USERS = 'delete users';

    /**
     * @var string
     */
    public const BAN_USERS = 'ban users';

    /**
     * @var string
     */
    public const VIEW_DEPARTMENT_USERS = 'view department users';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_USERS => Action::NAMES['create'],
            self::VIEW_USERS => Action::NAMES['view'],
            self::EDIT_USERS => Action::NAMES['edit'],
            self::DELETE_USERS => Action::NAMES['delete'],
            self::VIEW_DEPARTMENT_USERS => Action::NAMES['view'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return User::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_USERS => 'Create users',
            self::VIEW_USERS => 'View users',
            self::EDIT_USERS => 'Edit users',
            self::DELETE_USERS => 'Delete users',
            self::BAN_USERS => 'Ban users',
            self::VIEW_DEPARTMENT_USERS => 'View department users',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'User';
    }
}
