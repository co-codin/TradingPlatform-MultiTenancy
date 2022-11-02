<?php

namespace Modules\Desk\Dto;

use App\Dto\BaseDto;

class DeskDto extends BaseDto
{
    public ?string $name;

    public ?string $title;

    public ?bool $is_active;

    public ?int $parent_id;
}
