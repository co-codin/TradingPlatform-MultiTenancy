<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

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
            'name' => 'sometimes|required|string|max:35',
            'user_id' => [
                'sometimes',
                'required',
                'int',
                'exists:landlord.users,id',
                Rule::unique('tenant.communication_extensions')->ignore($this->route('extension'))->where(
                    fn ($query) => $query->where('provider_id', $this->provider_id)
                ),
            ],
            'provider_id' => [
                'sometimes',
                'required',
                'int',
                'exists:tenant.communication_providers,id',
                Rule::unique('tenant.communication_extensions')->ignore($this->route('extension'))->where(
                    fn ($query) => $query->where('user_id', $this->user_id)
                ),
            ],
        ];
    }
}
