<?php

namespace Modules\Department\Dto;

use App\Dto\BaseDto;

class DepartmentDto extends BaseDto
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
     * @var bool|null $is_active
     */
    public ?bool $is_active;

    /**
     * @var bool|null $is_default
     */
    public ?bool $is_default;
}
