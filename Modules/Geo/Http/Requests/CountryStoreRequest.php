<?php

namespace Modules\Geo\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryStoreRequest extends FormRequest
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
