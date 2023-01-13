<?php

declare(strict_types=1);

namespace Modules\Config\Enums;

use App\Enums\BaseEnum;

final class ConfigEnum extends BaseEnum
{
    /**
     * @var string
     */
    public const CUSTOMER_RESTRICTIONS = 'Customer restrictions';

    /**
     * @var string
     */
    public const  CHANGE_DEPARTMENT_DELAY = 'Change Department Delay (minutes)';
}
