<?php

namespace Modules\Brand\Dto;

use App\Dto\BaseDto;

class BrandDto extends BaseDto
{
    public ?string $name;

    public ?int $worker_id;

    public ?string $slug;

    public ?string $description;
}
