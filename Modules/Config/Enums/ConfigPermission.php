<?php

declare(strict_types=1);

namespace Modules\Config\Enums;

use App\Enums\BaseEnum;
use Modules\Role\Contracts\PermissionEnum;

final class ConfigPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    const CREATE_CONFIGS = 'create configs';

    /**
     * @var string
     */
    const VIEW_CONFIGS = 'view configs';

    /**
     * @var string
     */
    const EDIT_CONFIGS = 'edit configs';

    /**
     * @var string
     */
    const DELETE_CONFIGS = 'delete configs';

    /**
     * @inheritDoc
     */
    public static function module(): string
    {
        return 'Configs';
    }

    /**
     * @inheritDoc
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_CONFIGS => 'Create configs',
            self::VIEW_CONFIGS => 'View configs',
            self::EDIT_CONFIGS => 'Edit configs',
            self::DELETE_CONFIGS => 'Delete configs',
        ];
    }
}
