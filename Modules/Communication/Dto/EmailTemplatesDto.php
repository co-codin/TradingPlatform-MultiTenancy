<?php

declare(strict_types=1);

namespace Modules\Communication\Dto;

use App\Dto\BaseDto;

final class EmailTemplatesDto extends BaseDto
{
    public ?string $name;

    public ?string $body;
}
