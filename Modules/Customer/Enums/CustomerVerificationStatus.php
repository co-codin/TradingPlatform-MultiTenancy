<?php

declare(strict_types=1);

namespace Modules\Customer\Enums;

use App\Enums\BaseEnum;

final class CustomerVerificationStatus extends BaseEnum
{
    /**
     * @var string
     */
    public const NONE = 'none';

    /**
     * @var string
     */
    public const PARTIAL = 'partial';

    /**
     * @var string
     */
    public const FULL = 'full';
}
