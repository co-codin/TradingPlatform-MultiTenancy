<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class CommunicationExtensionStoreRequest extends BaseFormRequest
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
            'user_id' => 'required|int|exists:users,id',
            'provider_id' => 'required|int|exists:communication_providers,id',
        ];
    }
}
