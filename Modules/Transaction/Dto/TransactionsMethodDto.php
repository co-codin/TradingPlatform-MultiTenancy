<?php

declare(strict_types=1);

namespace Modules\Transaction\Dto;

use App\Dto\BaseDto;

final class TransactionsMethodDto extends BaseDto
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
     * @var bool|int|null $is_active
     */
    public ?bool $is_active;
}
