<?php

declare(strict_types=1);

namespace Modules\Campaign\Dto;

use App\Dto\BaseDto;

final class CampaignTransactionDto extends BaseDto
{
    public ?int $affiliate_id;
    public ?int $type;
    public ?float $amount;
    public ?array $customer_ids;
}
