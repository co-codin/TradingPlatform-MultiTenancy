<?php

declare(strict_types=1);

namespace Modules\Sale\Dto;

use App\Dto\BaseDto;

final class SaleDto extends BaseDto
{
    /**
     * @var string|null $name
     */
    public ?string $name;

    /**
     * @var string|null $title
     */
    public ?string $title;

    /**
     * @var string|null $color
     */
    public ?string $color;

    /**
     * @var bool|null $is_active
     */
    public ?bool $is_active;
}
