<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class CommunicationProviderStoreRequest extends BaseFormRequest
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
        ];
    }
}