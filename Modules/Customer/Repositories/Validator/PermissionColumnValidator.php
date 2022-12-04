<?php

declare(strict_types=1);

namespace Modules\Customer\Repositories\Validator;

use App\Repositories\BaseColumnPermissionValidator;
use Modules\Customer\Enums\CustomerPermission;

final class PermissionColumnValidator extends BaseColumnPermissionValidator
{
    /**
     * {@inheritDoc}
     */
    final protected function getRequestFieldName(): string
    {
        return 'customers';
    }

    /**
     * {@inheritDoc}
     */
    final protected function getBasePermissionName(): string
    {
        return CustomerPermission::VIEW_CUSTOMERS;
    }
}
