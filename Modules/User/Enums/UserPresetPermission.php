<?php

declare(strict_types=1);

namespace Modules\User\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\User\Models\Preset;

final class UserPresetPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const VIEW_USER_PRESETS = 'view user presets';

    /**
     * @var string
     */
    public const CREATE_USER_PRESETS = 'create user presets';

    /**
     * @var string
     */
    public const EDIT_USER_PRESETS = 'edit user presets';

    /**
     * @var string
     */
    public const DELETE_USER_PRESETS = 'delete user presets';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::VIEW_USER_PRESETS => Action::NAMES['view'],
            self::CREATE_USER_PRESETS => Action::NAMES['create'],
            self::EDIT_USER_PRESETS => Action::NAMES['edit'],
            self::DELETE_USER_PRESETS => Action::NAMES['delete'],
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
            self::VIEW_USER_PRESETS => 'View user presets',
            self::CREATE_USER_PRESETS => 'Create user presets',
            self::EDIT_USER_PRESETS => 'Edit user presets',
            self::DELETE_USER_PRESETS => 'Delete user presets',
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
