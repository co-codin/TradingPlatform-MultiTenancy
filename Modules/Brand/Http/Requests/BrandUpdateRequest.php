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
            'slug' => "sometimes|required|string|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:brands,slug,{$this->route('brand')}",
            'logo_url' => 'sometimes|required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
