<?php

declare(strict_types=1);

namespace Modules\Splitter\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class SplitterCreateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|int|exists:landlord.users,id',
            'name' => 'required|string|max:35',
            'is_active' => 'required|boolean',
            'conditions' => 'sometimes|array',
            'share_conditions' => 'sometimes|array',
            'position' => 'sometimes|integer',
        ];
    }
}
