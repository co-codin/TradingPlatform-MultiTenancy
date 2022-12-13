<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Dto;

use App\Dto\BaseDto;

final class CommunicationExtensionDto extends BaseDto
{
    public ?string $name;
    public ?int $provider_id;
    public ?int $user_id;
}
