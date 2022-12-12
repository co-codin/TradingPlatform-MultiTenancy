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
            'name' => 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'database' => 'required|string|regex:/^[a-z0-9]+(?:_[a-z0-9]+)*$/|unique:brands,database,' . $this->route('brand'),
            'domain' => 'required|string|regex:/^[a-z0-9]+(?:_[a-z0-9]+)*$/|unique:brands,domain,' . $this->route('brand'),
            'logo_url' => 'sometimes|required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
