<?php

declare(strict_types=1);

namespace Modules\Config\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class ConfigCreateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    final public function rules(): array
    {
        return [
            'config_type_id' => 'required|integer|exists:config_types,id',
            'data_type' => '',
            'name' => '',
            'value' => ''
        ];
    }
}
