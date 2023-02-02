<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests\DisplayOption;

use App\Http\Requests\BaseFormRequest;

final class UserDisplayOptionCreateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    final public function rules(): array
    {
        return [
            'model_name' => 'required|string|exists:landlord.models,name',
            'name' => 'required|string|max:35',
            'per_page' => 'nullable|integer',
            'columns' => 'nullable|array',
        ];
    }
}
