<?php

declare(strict_types=1);

namespace Modules\Role\Enums;

use BenSampo\Enum\Enum;

final class ModelHasPermissionStatus extends Enum
{
    /**
     * @var string
     */
    public const ACTIVE = 'active';

    /**
     * @var string
     */
    public const SUSPENDED = 'suspended';
}
