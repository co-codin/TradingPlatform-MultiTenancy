<?php

declare(strict_types=1);

namespace Modules\Campaign\Dto;

use App\Dto\BaseDto;

final class CampaignDto extends BaseDto
{
    public ?int $affiliate_id;

    public ?string $name;

    public ?float $cpa;

    public ?array $working_hours;

    public ?int $daily_cap;

    public ?float $crg;

    public ?bool $is_active;

    public ?bool $phone_verification = false;

    public ?float $balance;

    public ?int $monthly_cr;

    public ?int $monthly_pv;

    public ?float $crg_cost;

    public ?float $ftd_cost;

    public ?array $countries = null;
}
