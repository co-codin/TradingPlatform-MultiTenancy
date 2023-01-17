<?php

namespace Modules\Campaign\Enums;

use App\Enums\BaseEnum;

class CampaignTransactionType extends BaseEnum
{
    public const CORRECTION = 1;

    public const PAYMENT = 2;
}
