<?php

namespace Modules\Role\Dto;

use App\Dto\BaseDto;
use Illuminate\Foundation\Http\FormRequest;

class RoleDto extends BaseDto
{
    public ?string $name;

    public ?string $key;

    public ?string $guard_name = 'api';

    public ?array $permissions;

    public static function fromFormRequest(FormRequest $request): static
    {
        $validated = $request->validated();
        return new self([
            'name' => $validated['name'],
            'key' => $validated['key'],
            'permissions' => $validated['permissions'] ?? [],
        ]);
    }
}
