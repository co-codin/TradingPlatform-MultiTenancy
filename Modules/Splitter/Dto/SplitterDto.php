<?php

declare(strict_types=1);

namespace Modules\Splitter\Dto;

use App\Dto\BaseDto;

final class SplitterDto extends BaseDto
{
    public ?int $user_id;

    public ?string $name;

    public ?bool $is_active;

    public ?array $conditions;

    public ?int $position;
}
