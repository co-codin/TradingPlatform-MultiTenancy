<?php

declare(strict_types=1);

namespace Modules\Brand\Repositories;

use App\Repositories\BaseColumnPermissionValidator;
use Modules\Brand\Enums\BrandPermission;

final class BrandColumnPermissionValidator extends BaseColumnPermissionValidator
{
    /**
     * {@inheritDoc}
     */
    final protected function getRequestFieldName(): string
    {
        return 'brands';
    }

    /**
     * {@inheritDoc}
     */
    final protected function getBasePermissionName(): string
    {
        return BrandPermission::VIEW_BRANDS;
    }
}
