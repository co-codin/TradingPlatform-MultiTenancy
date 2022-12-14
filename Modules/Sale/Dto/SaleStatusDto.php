<?php

declare(strict_types=1);

namespace Modules\Sale\Dto;

use App\Dto\BaseDto;

final class SaleStatusDto extends BaseDto
{
    public ?string $name;

    public ?string $title;

    public ?string $color;

    public ?bool $is_active;

    public ?int $department_id;
}
