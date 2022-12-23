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
            'name' => "sometimes|required|string|unique:tenant.countries,name,{$this->route('country')}",
            'iso2' => "sometimes|required|string|unique:tenant.countries,iso2,{$this->route('country')}",
            'iso3' => "sometimes|required|string|unique:tenant.countries,iso3,{$this->route('country')}",
        ];
    }
}
