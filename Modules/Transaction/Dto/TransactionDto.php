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
     * @var int|null
     */
    public ?int $is_test;
    /**
     * @var int|null
     */
    public ?int $method_id;
}
