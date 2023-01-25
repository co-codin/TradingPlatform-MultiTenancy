<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests\Preset;

use App\Http\Requests\BaseFormRequest;

final class UserPresetCreateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    final public function rules(): array
    {
        return [
            'model_id' => 'required|integer|exists:landlord.models,id',
            'name' => 'required|string|max:35',
            'columns' => 'nullable|array',
            'columns.*' => 'required|string',
        ];
    }
}
