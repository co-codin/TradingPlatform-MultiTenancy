<?php

declare(strict_types=1);

namespace Modules\Geo\Dto;

use App\Dto\BaseDto;

final class CountryDto extends BaseDto
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
