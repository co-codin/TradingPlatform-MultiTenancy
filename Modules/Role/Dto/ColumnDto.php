<?php

declare(strict_types=1);

namespace Modules\Role\Dto;

use App\Dto\BaseDto;
use Illuminate\Foundation\Http\FormRequest;

final class ColumnDto extends BaseDto
{
    public ?string $name;

    public static function fromFormRequest(FormRequest $request): static
    {
        $validated = $request->validated();

        return new self([
            'name' => $validated['name'],
        ]);
    }
}
