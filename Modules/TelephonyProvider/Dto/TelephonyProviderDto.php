<?php

declare(strict_types=1);

namespace Modules\TelephonyProvider\Dto;

use App\Dto\BaseDto;

final class TelephonyProviderDto extends BaseDto
{
    /**
     * @var string|null
     */
    public ?string $name;

    /**
     * @var int|null
     */
    public ?int $user_id;

    /**
     * @var bool|null
     */
    public ?bool $is_default;
}
