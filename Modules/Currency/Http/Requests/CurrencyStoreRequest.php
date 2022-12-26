<?php

declare(strict_types=1);

namespace Modules\Currency\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class CurrencyStoreRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:tenant.currencies,name',
            'iso3' => 'required|string|unique:tenant.currencies,iso3',
            'symbol' => 'required|string',
            'is_available' => 'sometimes|required|boolean',
        ];
    }
}
