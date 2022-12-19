<?php

declare(strict_types=1);

namespace Modules\Communication\Dto;

use App\Dto\BaseDto;

final class CommunicationProviderDto extends BaseDto
{
    public ?string $name;
}
