<?php

namespace Modules\Config\Enums;

use App\Enums\BaseEnum;

class ConfigType extends BaseEnum
{
    public const CRM_FRONTEND = 'crm_frontend';

    public const PLATFORM_FRONTEND = 'platform_frontend';

    public const BACKEND = 'backend';
}
