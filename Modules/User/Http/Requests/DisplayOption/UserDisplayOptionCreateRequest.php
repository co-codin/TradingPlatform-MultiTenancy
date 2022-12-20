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
            'model_id' => 'required|integer|exists:landlord.models,id',
            'name' => 'required|string',
            'columns' => 'nullable|array',
            'columns.*' => 'sometimes|required|string',
        ];
    }
}
