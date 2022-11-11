<?php

namespace Modules\Geo\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Geo\Enums\CountryPermission;

class CountryUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'filled|string|unique:countries,name',
            'iso2' => 'filled|string|unique:countries,iso2',
            'iso3' => 'filled|string|unique:countries,iso3',
        ];
    }
}
