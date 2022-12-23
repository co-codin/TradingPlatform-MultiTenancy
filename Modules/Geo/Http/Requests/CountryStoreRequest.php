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
            'name' => 'required|string|unique:tenant.countries,name',
            'iso2' => 'required|string|unique:tenant.countries,iso2',
            'iso3' => 'required|string|unique:tenant.countries,iso3',
            'currency' => 'sometimes|required|string',
        ];
    }
}
