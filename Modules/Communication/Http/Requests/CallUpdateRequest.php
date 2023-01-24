<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class CallUpdateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'provider_id' => 'required|int|exists:tenant.communication_providers,id',
            'duration' => 'required|int',
            'text' => 'required|string|max:255',
            'status' => 'required|int',
        ];
    }
}
