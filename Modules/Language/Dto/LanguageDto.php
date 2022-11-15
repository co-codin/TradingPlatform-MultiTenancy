<?php

declare(strict_types=1);

namespace Modules\Language\Dto;

use App\Dto\BaseDto;

final class LanguageDto extends BaseDto
{
    /**
     * @var string|null $name
     */
    public ?string $name;

    /**
     * @var string|null $code
     */
    public ?string $code;
}
