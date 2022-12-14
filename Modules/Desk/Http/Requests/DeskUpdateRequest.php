<?php

declare(strict_types=1);

namespace Modules\Desk\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class DeskUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    final public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'is_active' => 'sometimes|boolean',
            'parent_id' => 'sometimes|int|exists:tenant.desks,id',
        ];
    }
}
