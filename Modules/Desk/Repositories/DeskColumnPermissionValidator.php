<?php

declare(strict_types=1);

namespace Modules\Desk\Repositories;

use App\Repositories\BaseColumnPermissionValidator;
use Modules\Desk\Enums\DeskPermission;

final class DeskColumnPermissionValidator extends BaseColumnPermissionValidator
{
    /**
     * {@inheritDoc}
     */
    final protected function getRequestFieldName(): string
    {
        return 'desks';
    }

    /**
     * {@inheritDoc}
     */
    final protected function getBasePermissionName(): string
    {
        return DeskPermission::VIEW_DESKS;
    }
}
