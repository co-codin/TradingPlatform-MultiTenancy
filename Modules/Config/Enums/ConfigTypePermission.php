<?php

declare(strict_types=1);

namespace Modules\Config\Enums;

use App\Enums\BaseEnum;
use Modules\Role\Contracts\PermissionEnum;

final class ConfigTypePermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    const CREATE_CONFIG_TYPES = 'create config types';

    /**
     * @var string
     */
    const VIEW_CONFIG_TYPES = 'view config types';

    /**
     * @var string
     */
    const EDIT_CONFIG_TYPES = 'edit config types';

    /**
     * @var string
     */
    const DELETE_CONFIG_TYPES = 'delete config types';

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
            self::CREATE_CONFIG_TYPES => 'Create config types',
            self::VIEW_CONFIG_TYPES => 'View config types',
            self::EDIT_CONFIG_TYPES => 'Edit config types',
            self::DELETE_CONFIG_TYPES => 'Delete config types',
        ];
    }
}
