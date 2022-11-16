<?php

declare(strict_types=1);

namespace Modules\Language\Enums;

use Modules\Role\Contracts\PermissionEnum;

final class LanguagePermission implements PermissionEnum
{
    /**
     * @var string
     */
    const CREATE_LANGUAGES = 'create languages';

    /**
     * @var string
     */
    const VIEW_LANGUAGES = 'view languages';

    /**
     * @var string
     */
    const EDIT_LANGUAGES = 'edit languages';

    /**
     * @var string
     */
    const DELETE_LANGUAGES = 'delete languages';

    /**
     * @inheritDoc
     */
    public static function module(): string
    {
        return 'Languages';
    }

    /**
     * @inheritDoc
     */
    public static function descriptions(): array
    {
        return [
            static::CREATE_LANGUAGES => 'Create languages',
            static::VIEW_LANGUAGES => 'View languages',
            static::EDIT_LANGUAGES => 'Edit languages',
            static::DELETE_LANGUAGES => 'Delete languages',
        ];
    }
}
