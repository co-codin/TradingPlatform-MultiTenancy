<?php

declare(strict_types=1);

namespace Modules\Config\Enums;

use App\Enums\BaseEnum;

final class ConfigTypeEnum extends BaseEnum
{
    /**
     * @var string
     */
    public const CRM_FRONTEND = 'crm_frontend';

    /**
     * @var string
     */
    public const PLATFORM_FRONTEND = 'platform_frontend';

    /**
     * @var string
     */
    public const BACKEND = 'backend';

    /**
     * @var string
     */
    public const TRANSACTION = 'transaction';
}
