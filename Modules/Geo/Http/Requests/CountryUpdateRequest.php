<?php

declare(strict_types=1);

namespace Modules\Geo\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class CountryUpdateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => "sometimes|required|string|max:35|unique:landlord.countries,name,{$this->route('country')}",
            'iso2' => "sometimes|required|string|max:2|unique:landlord.countries,iso2,{$this->route('country')}",
            'iso3' => "sometimes|required|string|max:3|unique:landlord.countries,iso3,{$this->route('country')}",
            'currency' => 'sometimes|required|string|max:35',
        ];
    }
}
