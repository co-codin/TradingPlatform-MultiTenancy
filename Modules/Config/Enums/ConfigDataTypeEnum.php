<?php

declare(strict_types=1);

namespace Modules\Config\Enums;

use App\Enums\BaseEnum;

final class ConfigDataTypeEnum extends BaseEnum
{
    /**
     * @var string
     */
    public const JSON = 'json';

    /**
     * @var string
     */
    public const STRING = 'string';

    /**
     * @var string
     */
    public const BOOLEAN = 'boolean';

    /**
     * @var string
     */
    public const INTEGER = 'integer';
}
