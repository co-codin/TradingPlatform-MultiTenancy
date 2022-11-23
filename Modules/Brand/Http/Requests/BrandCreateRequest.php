<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;
use Modules\Brand\Services\BrandDBService;

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
            'slug' => 'required|string|regex:/^[a-z0-9_\]*$/|unique:brands,slug',
            'logo_url' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
            'tables' => [
                'sometimes',
                'array',
                Rule::in(BrandDBService::ALLOWED_MODULES),
            ],
        ];
    }
}
