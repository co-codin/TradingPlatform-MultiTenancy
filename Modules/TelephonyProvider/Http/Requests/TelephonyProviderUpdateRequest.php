<?php

declare(strict_types=1);

namespace Modules\TelephonyProvider\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class TelephonyProviderUpdateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'is_default' => 'sometimes|bool',
            'user_id' => 'sometimes|int|exists:users,id',
        ];
    }
}
