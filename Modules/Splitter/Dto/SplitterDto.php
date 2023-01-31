<?php

declare(strict_types=1);

namespace Modules\Splitter\Dto;

use App\Dto\BaseDto;

final class SplitterDto extends BaseDto
{
    public ?string $name;

    public ?bool $is_active;

    public ?array $conditions;

    public ?array $share_conditions;

    public ?array $splitter_choice;
}
