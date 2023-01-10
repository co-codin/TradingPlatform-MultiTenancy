<?php

declare(strict_types=1);

namespace Modules\Transaction\Dto;

use App\Dto\BaseDto;

final class TransactionDto extends BaseDto
{
    public ?string $type;
    public ?string $mt5_type;
    public ?string $status;

    public ?float $amount;
    public ?int $customer_id;
    public ?int $method_id;
    public ?int $wallet_id;

    public ?string $external_id;
    public ?string $description;
    public ?bool $is_test;
}
