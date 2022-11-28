<?php

declare(strict_types=1);

namespace Modules\Token\Repositories;

use App\Repositories\BaseColumnPermissionValidator;
use Modules\Token\Enums\TokenPermission;

final class TokenColumnPermissionValidator extends BaseColumnPermissionValidator
{
    /**
     * {@inheritDoc}
     */
    final protected function getRequestFieldName(): string
    {
        return 'tokens';
    }

    /**
     * {@inheritDoc}
     */
    final protected function getBasePermissionName(): string
    {
        return TokenPermission::VIEW_TOKENS;
    }
}
