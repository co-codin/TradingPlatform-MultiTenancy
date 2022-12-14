<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Role\Enums\ModelHasPermissionStatus;

final class CustomerUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'affiliate_user_id' => 'sometimes|required|exists:public.users,id',
            'conversion_user_id' => 'sometimes|required|exists:public.users,id',
            'retention_user_id' => 'sometimes|required|exists:public.users,id',
            'compliance_user_id' => 'sometimes|required|exists:public.users,id',
            'support_user_id' => 'sometimes|required|exists:public.users,id',
            'conversion_manager_user_id' => 'sometimes|required|exists:public.users,id',
            'retention_manager_user_id' => 'sometimes|required|exists:public.users,id',
            'first_conversion_user_id' => 'sometimes|required|exists:public.users,id',
            'first_retention_user_id' => 'sometimes|required|exists:public.users,id',
            'conversion_sale_status_id' => 'sometimes|required|exists:tenant.sale_statuses,id',
            'retention_sale_status_id' => 'sometimes|required|exists:tenant.sale_statuses,id',
            'permissions' => 'sometimes|required|array',
            'permissions.*.id' => 'required',
            'permissions.*.status' => [
                'sometimes',
                'required',
                new EnumValue(ModelHasPermissionStatus::class, false),
            ],
            'permissions.*.body.reason' => 'sometimes|required|string',
        ];
    }
}
