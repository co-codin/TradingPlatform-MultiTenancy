<?php

declare(strict_types=1);

namespace Modules\Currency\Dto;

use App\Dto\BaseDto;

final class CurrencyDto extends BaseDto
{
    /**
     * @var string|null
     */
    public ?string $name;

    /**
     * @var string|null
     */
    public ?string $iso3;

    /**
     * @var string|null
     */
    public ?string $symbol;

    /**
     * @var bool|int|null
     */
    public bool|int|null $is_available;
}
