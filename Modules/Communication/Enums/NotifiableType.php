<?php

declare(strict_types=1);

namespace Modules\Communication\Enums;

use App\Enums\BaseEnum;

final class NotifiableType extends BaseEnum
{
    /**
     * @var string
     */
    public const USER = 'user';

    /**
     * @var string
     */
    public const CUSTOMER = 'customer';
}
