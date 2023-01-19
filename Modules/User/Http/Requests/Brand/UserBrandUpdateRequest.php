<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests\Brand;

use App\Http\Requests\BaseFormRequest;

final class UserBrandUpdateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'brands' => 'present|array',
            'brands.*.id' => 'distinct|integer|exists:landlord.brands,id',
        ];
    }
}
