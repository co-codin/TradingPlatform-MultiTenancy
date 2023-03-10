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
            'model_name' => 'sometimes|required|string|exists:landlord.models,name',
            'name' => 'sometimes|required|string|max:35',
            'per_page' => 'nullable|integer',
            'columns' => 'nullable|array',
        ];
    }
}
