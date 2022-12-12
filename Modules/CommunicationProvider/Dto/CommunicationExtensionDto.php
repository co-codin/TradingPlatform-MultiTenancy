<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Dto;

use App\Dto\BaseDto;

final class CommunicationExtensionDto extends BaseDto
{
    /**
     * @var string|null
     */
    public ?string $name;

    /**
     * @var int|null
     */
    public ?int $provider_id;
}
