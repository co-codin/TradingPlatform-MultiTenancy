<?php

declare(strict_types=1);

namespace Modules\Department\Enums;

use BenSampo\Enum\Enum;

final class DepartmentEnum extends Enum
{
    /**
     * @var string
     */
    public const CONVERSION = 'conversion';

    /**
     * @var string
     */
    public const RETENTION = 'retention';
}
