<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests\DisplayOption;

use App\Http\Requests\BaseFormRequest;

final class UserDisplayOptionUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    final public function rules(): array
    {
        return [
            'model_id' => 'sometimes|required|integer|exists:landlord.models,id',
            'name' => 'sometimes|required|string',
            'columns' => 'nullable|array',
            'columns.*.name' => 'sometimes|required|string',
            'columns.*.position' => 'sometimes|required|integer',
        ];
    }
}
