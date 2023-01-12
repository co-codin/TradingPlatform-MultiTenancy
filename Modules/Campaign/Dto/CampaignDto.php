<?php

declare(strict_types=1);

namespace Modules\Campaign\Dto;

use App\Dto\BaseDto;

final class CampaignDto extends BaseDto
{
    /**
     * @var float|null $cpa
     */
    public ?float $cpa;

    /**
     * @var array|null $working_hours
     */
    public ?array $working_hours;

    /**
     * @var int|null $daily_cap
     */
    public ?int $daily_cap;

    /**
     * @var float|null $crg
     */
    public ?float $crg;
}
