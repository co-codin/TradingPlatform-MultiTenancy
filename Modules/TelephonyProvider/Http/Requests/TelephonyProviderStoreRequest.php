<?php

declare(strict_types=1);

namespace Modules\TelephonyProvider\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class TelephonyProviderStoreRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'is_default' => 'required|bool',
            'user_id' => 'required|int|exists:users,id',
        ];
    }
}
