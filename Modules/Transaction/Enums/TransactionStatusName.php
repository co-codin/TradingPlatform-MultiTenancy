<?php

declare(strict_types=1);

namespace Modules\Transaction\Enums;

use BenSampo\Enum\Enum;

final class TransactionStatusName extends Enum
{
    /**
     * @var string
     */
    public const COMPLETED = 'Completed';

    /**
     * @var string
     */
    public const DECLINED = 'Declined';

    /**
     * @var string
     */
    public const CANCELED = 'Canceled';

    /**
     * @var string
     */
    public const PENDING = 'Pending';
}
