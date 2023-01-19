<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests\Country;

use App\Http\Requests\BaseFormRequest;

final class UserCountryUpdateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'countries' => 'present|array',
            'countries.*.id' => 'distinct|integer|exists:landlord.countries,id',
        ];
    }
}
