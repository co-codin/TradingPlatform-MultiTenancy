<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class CustomerUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'affiliate_user_id' => 'sometimes|required|exists:users,id',
            'conversion_user_id' => 'sometimes|required|exists:users,id',
            'retention_user_id' => 'sometimes|required|exists:users,id',
            'compliance_user_id' => 'sometimes|required|exists:users,id',
            'support_user_id' => 'sometimes|required|exists:users,id',
            'conversion_manager_user_id' => 'sometimes|required|exists:users,id',
            'retention_manager_user_id' => 'sometimes|required|exists:users,id',
            'first_conversion_user_id' => 'sometimes|required|exists:users,id',
            'first_retention_user_id' => 'sometimes|required|exists:users,id',
        ];
    }
}
