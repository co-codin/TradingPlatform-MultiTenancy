<?php

namespace Modules\Role\Dto;

use App\Dto\BaseDto;
use Illuminate\Foundation\Http\FormRequest;

class PermissionDto extends BaseDto
{
    public ?string $name;

    public ?string $description;

    public ?string $guard_name;

    public ?array $role_ids;

    public static function fromFormRequest(FormRequest $request): static
    {
        $validated = $request->validated();
        return new self([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'guard_name' => 'api',
            'role_ids' => $validated['role_ids'],
        ]);
    }
}
