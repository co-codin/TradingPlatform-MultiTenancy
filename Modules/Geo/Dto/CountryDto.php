<?php

namespace Modules\Geo\Dto;

use App\Dto\BaseDto;

class CountryDto extends BaseDto
{
    /**
     * @var string|null $name
     */
    public ?string $name;

    /**
     * @var string|null $iso2
     */
    public ?string $iso2;

    /**
     * @var string|null $iso3
     */
    public ?string $iso3;
}
