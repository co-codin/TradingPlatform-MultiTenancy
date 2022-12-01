<?php

declare(strict_types=1);

namespace Modules\Config\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Validation\Rule;
use Modules\Config\Enums\ConfigType;
use Modules\Config\Enums\DataType;

final class ConfigCreateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    final public function rules(): array
    {
        return [
            'config_type_id' => 'required|integer|exists:config_types,id',
            'data_type' => [
                'required',
                'string',
                new EnumValue(ConfigType::class, false)
            ],
            'name' => 'required|string',
            'value' => 'required|string',
        ];
    }
}
