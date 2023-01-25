<?php

namespace Modules\Geo\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class CountryStoreRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:35|unique:landlord.countries,name',
            'iso2' => 'required|string|max:2|unique:landlord.countries,iso2',
            'iso3' => 'required|string|max:3|unique:landlord.countries,iso3',
            'currency' => 'sometimes|required|string|max:35',
        ];
    }
}
