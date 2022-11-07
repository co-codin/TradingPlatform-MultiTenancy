<?php

namespace Modules\Brand\Dto;

use App\Dto\BaseDto;

class BrandDto extends BaseDto
{
    public ?string $name;

    public ?string $title;

    public ?string $logo_url;

    public ?string $slug;

    public $is_active = 1;
}
