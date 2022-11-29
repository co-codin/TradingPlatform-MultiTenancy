<?php

declare(strict_types=1);

namespace Modules\Config\Enums;

use App\Enums\BaseEnum;

final class DataType extends BaseEnum
{
    /**
     * @var string
     */
    public const JSON = 'json';

    /**
     * @var string
     */
    public const STRING = 'string';
}
