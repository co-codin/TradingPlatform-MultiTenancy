<?php

declare(strict_types=1);

namespace Modules\User\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\User\Models\DisplayOption;
use Modules\User\Models\Preset;

final class UserPresetPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const VIEW_USER_PRESET_OPTIONS = 'view user display options';

    /**
     * @var string
     */
    public const CREATE_USER_PRESET_OPTIONS = 'create user display options';

    /**
     * @var string
     */
    public const EDIT_USER_PRESET_OPTIONS = 'edit user display options';

    /**
     * @var string
     */
    public const DELETE_USER_PRESET_OPTIONS = 'delete user display options';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::VIEW_USER_PRESET_OPTIONS => Action::NAMES['view'],
            self::CREATE_USER_PRESET_OPTIONS => Action::NAMES['create'],
            self::EDIT_USER_PRESET_OPTIONS => Action::NAMES['edit'],
            self::DELETE_USER_PRESET_OPTIONS => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Preset::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::VIEW_USER_PRESET_OPTIONS => 'View user display options',
            self::CREATE_USER_PRESET_OPTIONS => 'Create user display options',
            self::EDIT_USER_PRESET_OPTIONS => 'Edit user display options',
            self::DELETE_USER_PRESET_OPTIONS => 'Delete user display options',
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
