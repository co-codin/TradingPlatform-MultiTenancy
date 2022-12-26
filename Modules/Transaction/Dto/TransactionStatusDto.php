<?php

declare(strict_types=1);

namespace Modules\Transaction\Dto;

use App\Dto\BaseDto;

final class TransactionStatusDto extends BaseDto
{
    /**
     * @var string|null $username
     */
    public ?string $name;

    /**
     * @var string|null $title
     */
    public ?string $title;

    /**
     * @var bool|int|null $is_active
     */
    public bool|int|null $is_active;

    /**
     * @var bool|int|null $is_valid
     */
    public bool|int|null $is_valid;
}
