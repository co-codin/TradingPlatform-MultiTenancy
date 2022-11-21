<?php

namespace Modules\User\Dto;

use App\Dto\BaseDto;

class DisplayOptionColumnItemDto extends BaseDto
{
    /**
     * @var string
     */
    public string $value = '';

    /**
     * @var bool
     */
    public bool $visible = true;

    /**
     * @var int
     */
    public int $order = 0;
}
