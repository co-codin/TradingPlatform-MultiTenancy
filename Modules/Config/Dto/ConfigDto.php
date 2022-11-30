<?php

declare(strict_types=1);

namespace Modules\Config\Dto;

use App\Dto\BaseDto;

final class ConfigDto extends BaseDto
{
    /**
     * @var int|null $config_type_id
     */
    public ?int $config_type_id;

    /**
     * @var string|null $name
     */
    public ?string $data_type;

    /**
     * @var string|null $name
     */
    public ?string $name;

    /**
     * @var string|null $value
     */
    public ?string $value;
}
