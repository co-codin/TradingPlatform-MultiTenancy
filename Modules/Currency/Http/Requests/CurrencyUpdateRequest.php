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
            'name' => 'sometimes|required|string|max:35|unique:landlord.currencies,name',
            'iso3' => 'sometimes|required|string|max:3|unique:landlord.currencies,iso3',
            'symbol' => 'sometimes|required|string|max:3',
            'is_available' => 'sometimes|required|boolean',
        ];
    }
}
