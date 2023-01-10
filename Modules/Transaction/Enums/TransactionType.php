<?php

declare(strict_types=1);

namespace Modules\Transaction\Enums;

use BenSampo\Enum\Enum;

final class TransactionType extends Enum
{
    /**
     * @var string
     */
    public const WITHDRAWAL = 'withdrawal';

    /**
     * @var string
     */
    public const DEPOSIT = 'deposit';
}
