<?php

declare(strict_types=1);

namespace Modules\User\Dto;

use App\Dto\BaseDto;

final class DisplayOptionColumnItemDto extends BaseDto
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
