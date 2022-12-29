<?php

declare(strict_types=1);

namespace Modules\Transaction\Enums;

use BenSampo\Enum\Enum;

final class TransactionStatusName extends Enum
{
    /**
     * @var string
     */
    public const APPROVED = 'approved';

    /**
     * @var string
     */
    public const DECLINED = 'declined';

    /**
     * @var string
     */
    public const CANCELED = 'canceled';

    /**
     * @var string
     */
    public const PENDING = 'pending';
}
