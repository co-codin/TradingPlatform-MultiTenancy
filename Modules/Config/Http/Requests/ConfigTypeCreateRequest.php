<?php

declare(strict_types=1);

namespace Modules\Config\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class ConfigTypeCreateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    final public function rules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }
}
