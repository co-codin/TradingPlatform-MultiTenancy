<?php

declare(strict_types=1);

namespace Modules\Desk\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class DeskCreateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    final public function rules(): array
    {
        return [
            'name' => 'required|string|max:35',
            'title' => 'required|string|max:35',
            'is_active' => 'sometimes|boolean',
            'parent_id' => 'sometimes|int|exists:tenant.desks,id',
        ];
    }
}
