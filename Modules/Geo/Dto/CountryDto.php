<?php

declare(strict_types=1);

namespace Modules\Geo\Dto;

use App\Dto\BaseDto;

final class CountryDto extends BaseDto
{
    /**
     * @var string|null
     */
    public ?string $name;

    /**
     * @var string|null
     */
    public ?string $iso2;

    /**
     * @var string|null
     */
    public ?string $iso3;
}
