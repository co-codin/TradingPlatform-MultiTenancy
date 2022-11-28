<?php

declare(strict_types=1);

namespace Modules\Language\Repositories;

use App\Repositories\BaseColumnPermissionValidator;
use Modules\Language\Enums\LanguagePermission;

final class LanguageColumnPermissionValidator extends BaseColumnPermissionValidator
{
    /**
     * {@inheritDoc}
     */
    final protected function getRequestFieldName(): string
    {
        return 'languages';
    }

    /**
     * {@inheritDoc}
     */
    final protected function getBasePermissionName(): string
    {
        return LanguagePermission::VIEW_LANGUAGES;
    }
}
