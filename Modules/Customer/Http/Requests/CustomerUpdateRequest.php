<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Customer\Enums\Gender;
use Modules\Role\Enums\ModelHasPermissionStatus;

final class CustomerUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'gender' => [
                'sometimes',
                'required',
                new EnumValue(Gender::class, false),
            ],
            'phone' => 'sometimes|required|string',
            'country_id' => 'sometimes|required|int|exists:tenant.countries,id',
            'phone2' => 'sometimes',
            'language_id' => 'sometimes|required|int|exists:tenant.languages,id',
            'city' => 'sometimes|string',
            'address' => 'sometimes|string',
            'postal_code' => 'sometimes|string',
            'desk_id' => 'sometimes|integer|exists:tenant.desks,id',
            'department_id' => 'sometimes|integer|exists:tenant.departments,id',
            'offer_name' => 'sometimes|string',
            'offer_url' => 'sometimes|string',
            'comment_about_customer' => 'sometimes|string',
            'source' => 'sometimes|string',
            'click_id' => 'sometimes|string',
            'free_param_1' => 'sometimes|string',
            'free_param_2' => 'sometimes|string',
            'free_param_3' => 'sometimes|string',
            'affiliate_user_id' => 'sometimes|required|exists:landlord.users,id',
            'conversion_user_id' => 'sometimes|required|exists:landlord.users,id',
            'retention_user_id' => 'sometimes|required|exists:landlord.users,id',
            'compliance_user_id' => 'sometimes|required|exists:landlord.users,id',
            'support_user_id' => 'sometimes|required|exists:landlord.users,id',
            'conversion_manager_user_id' => 'sometimes|required|exists:landlord.users,id',
            'retention_manager_user_id' => 'sometimes|required|exists:landlord.users,id',
            'first_conversion_user_id' => 'sometimes|required|exists:landlord.users,id',
            'first_retention_user_id' => 'sometimes|required|exists:landlord.users,id',
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
