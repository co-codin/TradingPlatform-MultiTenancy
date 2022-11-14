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
            'name' => "sometimes|required|string|unique:countries,name,{$this->route('country')}",
            'iso2' => "sometimes|required|string|unique:countries,iso2,{$this->route('country')}",
            'iso3' => "sometimes|required|string|unique:countries,iso3,{$this->route('country')}",
        ];
    }
}
