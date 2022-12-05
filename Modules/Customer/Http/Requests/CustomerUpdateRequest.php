<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class CustomerUpdateRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_conversion_user_id' => 'sometimes|required|exists:users,id',
        ];
    }
}
