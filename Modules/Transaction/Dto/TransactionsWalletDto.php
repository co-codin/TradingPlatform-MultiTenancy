<?php

declare(strict_types=1);

namespace Modules\Transaction\Dto;

use App\Dto\BaseDto;

final class TransactionsWalletDto extends BaseDto
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
     * @var string|null $mt5_id
     */
    public ?string $mt5_id;

    /**
     * @var int|null $currency_id
     */
    public ?int $currency_id;
}
