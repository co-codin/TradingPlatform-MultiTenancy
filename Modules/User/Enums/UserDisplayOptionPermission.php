<?php

declare(strict_types=1);

namespace Modules\User\Enums;

use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;

final class UserDisplayOptionPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_USER_DISPLAY_OPTIONS = 'create user display options';

    /**
     * @var string
     */
    public const EDIT_USER_DISPLAY_OPTIONS = 'edit user display options';

    /**
     * @var string
     */
    public const DELETE_USER_DISPLAY_OPTIONS = 'delete user display options';

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_USER_DISPLAY_OPTIONS => 'Create user display options',
            self::EDIT_USER_DISPLAY_OPTIONS => 'Edit user display options',
            self::DELETE_USER_DISPLAY_OPTIONS => 'Delete user display options',
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
