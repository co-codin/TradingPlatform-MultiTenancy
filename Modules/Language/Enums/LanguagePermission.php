<?php

declare(strict_types=1);

namespace Modules\Language\Enums;

use App\Enums\BaseEnum;
use App\Models\Action;
use Modules\Language\Models\Language;
use Modules\Role\Contracts\PermissionEnum;

final class LanguagePermission extends BaseEnum implements PermissionEnum
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
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_LANGUAGES => Action::NAMES['create'],
            self::VIEW_LANGUAGES => Action::NAMES['view'],
            self::EDIT_LANGUAGES => Action::NAMES['edit'],
            self::DELETE_LANGUAGES => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Language::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Languages';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_LANGUAGES => 'Create languages',
            self::VIEW_LANGUAGES => 'View languages',
            self::EDIT_LANGUAGES => 'Edit languages',
            self::DELETE_LANGUAGES => 'Delete languages',
        ];
    }
}
