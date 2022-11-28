<?php

namespace Modules\Brand\Repositories;

use App\Repositories\BaseColumnPermissionValidator;
use Modules\Brand\Enums\BrandPermission;

class BrandColumnPermissionValidator extends BaseColumnPermissionValidator
{
    /**
     * {@inheritDoc}
     */
    protected function getRequestFieldName(): string
    {
        return 'brands';
    }

    /**
     * {@inheritDoc}
     */
    protected function getBasePermissionName(): string
    {
        return BrandPermission::VIEW_BRANDS;
    }
}
