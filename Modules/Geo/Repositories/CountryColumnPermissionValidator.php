<?php

declare(strict_types=1);

namespace Modules\Geo\Repositories;

use App\Repositories\BaseColumnPermissionValidator;
use Modules\Geo\Enums\CountryPermission;

final class CountryColumnPermissionValidator extends BaseColumnPermissionValidator
{
    /**
     * {@inheritDoc}
     */
    final protected function getRequestFieldName(): string
    {
        return 'countries';
    }

    /**
     * {@inheritDoc}
     */
    final protected function getBasePermissionName(): string
    {
        return CountryPermission::VIEW_COUNTRIES;
    }
}
