<?php

declare(strict_types=1);

namespace Modules\Config\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Config\Enums\ConfigDataTypeEnum;

final class ConfigUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    final public function rules(): array
    {
        return [
            'config_type_id' => 'sometimes|required|integer|exists:tenant.config_types,id',
            'data_type' => [
                'sometimes',
                'required',
                'string',
                new EnumValue(ConfigDataTypeEnum::class, false),
            ],
            'name' => 'sometimes|required|string',
            'value' => 'sometimes|required|string',
        ];
    }
}
