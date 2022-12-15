<?php

declare(strict_types=1);

namespace Modules\Token\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class TokenCreateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    final public function rules(): array
    {
        return [
            'user_id' => 'required|int|exists:landlord.users,id',
            'token' => 'required|string',
            'description' => 'sometimes|nullable',
            'ip' => 'required|string',
        ];
    }
}
