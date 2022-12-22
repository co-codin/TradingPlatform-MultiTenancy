<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class CommunicationExtensionBulkReplaceRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|int|exists:landlord.users,id',
            'extensions' => 'required|array|max:255',
            'extensions.*.name' => 'required|string',
            'extensions.*.provider_id' => [
                'required',
                'distinct',
                'int',
                'exists:tenant.communication_providers,id',
            ],
        ];
    }
}
