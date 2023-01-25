<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class BrandUpdateRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    final public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:35',
            'title' => 'sometimes|required|string|max:35',
            'database' => 'required|string|max:35|regex:/^[a-z0-9]+(?:_[a-z0-9]+)*$/|unique:landlord.brands,database,'.$this->route('brand'),
            'domain' => 'required|string|max:35|regex:/^[a-z0-9]+(?:_[a-z0-9]+)*$/|unique:landlord.brands,domain,'.$this->route('brand'),
            'logo_url' => 'sometimes|required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
