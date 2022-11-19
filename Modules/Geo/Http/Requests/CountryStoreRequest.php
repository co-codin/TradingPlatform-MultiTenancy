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
            'name' => 'required|string|unique:countries,name',
            'iso2' => 'required|string|unique:countries,iso2',
            'iso3' => 'required|string|unique:countries,iso3',
        ];
    }
}
