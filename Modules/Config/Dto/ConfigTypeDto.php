<?php

declare(strict_types=1);

namespace Modules\Config\Dto;

use App\Dto\BaseDto;

final class ConfigTypeDto extends BaseDto
{
    /**
     * @var string|null $name
     */
    public ?string $name;
}
