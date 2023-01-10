<?php

declare(strict_types=1);

namespace Modules\Transaction\Dto;

use App\Dto\BaseDto;

final class TransactionDto extends BaseDto
{
    /**
     * @var int|null
     */
    public ?int $status_id;
    /**
     * @var int|null
     */
    public ?int $worker_id;
    /**
     * @var bool|int|null
     */
    public ?bool $is_test;
    /**
     * @var int|null
     */
    public ?int $method_id;
    /**
     * @var string|null
     */
    public ?string $description;
    /**
     * @var string|null
     */
    public ?string $external_id;
    /**
     * @var float|null
     */
    public ?float $amount;
}
// -Amount(если статус Pending. При изменении суммы, нужно также проверять курс валют и записывать поле amount_usd)
