<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class CommunicationExtensionUpdateRequest extends BaseFormRequest
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
            'user_id' => 'sometimes|int|exists:public.users,id',
            'provider_id' => 'sometimes|int|exists:public.communication_providers,id',
        ];
    }
}
