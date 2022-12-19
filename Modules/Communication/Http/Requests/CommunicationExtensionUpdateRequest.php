<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Requests;

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
            'name' => 'sometimes|required|string',
            'user_id' => 'sometimes|required|int|exists:landlord.users,id',
            'provider_id' => 'sometimes|required|int|exists:tenant.communication_providers,id',
        ];
    }
}
