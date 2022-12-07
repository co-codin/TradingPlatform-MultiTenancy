<?php

namespace Modules\Brand\Dto;

use App\Dto\BaseDto;

class BrandDto extends BaseDto
{
    public ?string $name;

    public ?string $title;

    public ?string $logo_url;

    public ?string $domain;

    public ?string $database;

    public $is_active = 1;
}
