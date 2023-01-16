<?php

declare(strict_types=1);

namespace Modules\Transaction\Dto;

use App\Dto\BaseDto;

final class WalletDto extends BaseDto
{
    /**
     * @var string|null
     */
    public ?string $name;

    /**
     * @var string|null
     */
    public ?string $title;

    /**
     * @var string|null
     */
    public ?string $mt5_id;

    /**
     * @var int|null
     */
    public ?int $currency_id;

    /**
     * @var int|null
     */
    public ?int $customer_id;
}
