<?php

declare(strict_types=1);

namespace Modules\Token\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class TokenUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    final public function rules(): array
    {
        return [
            'user_id' => 'sometimes|required|int|exists:landlord.users,id',
            'token' => 'sometimes|required|string',
            'description' => 'sometimes|nullable',
            'ip' => 'sometimes|required|string',
        ];
    }
}
