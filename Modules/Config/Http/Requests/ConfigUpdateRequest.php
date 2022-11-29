<?php

declare(strict_types=1);

namespace Modules\Config\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;
use Modules\Config\Enums\DataType;

final class ConfigUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    final public function rules(): array
    {
        return [
            'config_type_id' => 'sometimes|required|integer|exists:config_types,id',
            'data_type' => [
                'sometimes',
                'required',
                'string',
                Rule::in(DataType::getValues())
            ],
            'name' => 'sometimes|required|string',
            'value' => 'sometimes|required|string',
        ];
    }
}
