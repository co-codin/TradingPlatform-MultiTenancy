<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class BrandCreateRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    final public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'database' => 'required|string|regex:/^[a-z0-9]+(?:_[a-z0-9]+)*$/|unique:brands,database',
            'domain' => 'required|string|regex:/^[a-z0-9]+(?:_[a-z0-9]+)*$/|unique:brands,domain',
            'logo_url' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
