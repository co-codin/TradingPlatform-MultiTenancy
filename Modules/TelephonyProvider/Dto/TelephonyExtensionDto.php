<?php

declare(strict_types=1);

namespace Modules\TelephonyProvider\Dto;

use App\Dto\BaseDto;

final class TelephonyExtensionDto extends BaseDto
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
