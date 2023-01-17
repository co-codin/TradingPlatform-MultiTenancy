<?php

declare(strict_types=1);

namespace Modules\Role\Dto;

use App\Dto\BaseDto;

final class RoleDto extends BaseDto
{
    public ?string $name;

    public ?string $key;

    public ?string $guard_name = 'api';

    public ?array $permissions;

    public ?bool $is_default;
}
