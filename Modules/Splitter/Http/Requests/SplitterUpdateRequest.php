<?php

declare(strict_types=1);

namespace Modules\Splitter\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class SplitterUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:35',
            'is_active' => 'required|boolean',
            'conditions' => 'sometimes|array',
            'position' => 'sometimes|integer',
        ];
    }
}
