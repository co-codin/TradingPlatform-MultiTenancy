<?php

declare(strict_types=1);

namespace Modules\Transaction\Enums;

use BenSampo\Enum\Enum;

final class TransactionMt5TypeEnum extends Enum
{
    /**
     * @var string
     */
    public const BALANCE = 'balance';

    /**
     * @var string
     */
    public const CREDIT = 'credit';

    /**
     * @var string
     */
    public const CHARGE = 'charge';

    /**
     * @var string
     */
    public const CORRECTION = 'correction';

    /**
     * @var string
     */
    public const BONUS = 'bonus';
}
