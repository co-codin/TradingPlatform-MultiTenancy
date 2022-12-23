<?php

declare(strict_types=1);

namespace Modules\Currency\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class CurrencyUpdateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|unique:tenant.currencies,name',
            'iso3' => 'sometimes|required|string|unique:tenant.currencies,iso3',
            'symbol' => 'sometimes|required|string',
            'is_available' => 'sometimes|required|boolean',
        ];
    }
}
