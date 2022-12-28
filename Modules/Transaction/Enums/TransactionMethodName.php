<?php

declare(strict_types=1);

namespace Modules\Transaction\Enums;

use BenSampo\Enum\Enum;

final class TransactionMethodName extends Enum
{
    /**
     * @var string
     */
    public const WITHDRAW = 'withdraw';

    /**
     * @var string
     */
    public const DEPOSIT = 'deposit';
}
