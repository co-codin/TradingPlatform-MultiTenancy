<?php

declare(strict_types=1);

namespace Modules\Communication\Dto;

use App\Dto\BaseDto;

final class CallDto extends BaseDto
{
    public ?int $user_id;
    public ?int $provider_id;
    public ?int $duration;
    public ?string $text;
    public ?int $status;
}
