<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use App\Enums\RegexValidationEnum;
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
            'first_name' => 'sometimes|required|string|max:35|regex:'.RegexValidationEnum::NAME,
            'last_name' => 'sometimes|required|string|max:35|regex:'.RegexValidationEnum::NAME,
            'email_2' => 'sometimes|required|email|max:100',
            'gender' => [
                'sometimes',
                'required',
                new EnumValue(Gender::class, false),
            ],
            'country_id' => 'sometimes|required|int|exists:landlord.countries,id',
            'phone' => [
                'sometimes',
                'required',
                'string',
                'regex:'.RegexValidationEnum::PHONE,
            ],
            'phone_2' => [
                'sometimes',
                'required',
                'string',
                'regex:'.RegexValidationEnum::PHONE,
            ],
            'currency_id' => 'sometimes|required|int|exists:landlord.currencies,id',
            'language_id' => 'sometimes|required|int|exists:landlord.languages,id',
            'platform_language_id' => 'sometimes|required|int|exists:landlord.languages,id',
            'browser_language_id' => 'sometimes|required|int|exists:landlord.languages,id',
            'city' => 'sometimes|string|max:35',
            'address' => 'sometimes|string|max:100',
            'postal_code' => 'sometimes|string|max:35',
            'desk_id' => 'sometimes|integer|exists:tenant.desks,id',
            'department_id' => 'sometimes|integer|exists:tenant.departments,id',
            'offer_name' => 'sometimes|string|max:35',
            'offer_url' => 'sometimes|string',
            'comment_about_customer' => 'sometimes|string|max:255',
            'source' => 'sometimes|string|max:35',
            'click_id' => 'sometimes|string|max:35',
            'free_param_1' => 'sometimes|string|max:35',
            'free_param_2' => 'sometimes|string|max:35',
            'free_param_3' => 'sometimes|string|max:35',
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
            'permissions.*.pivot.status' => [
                'sometimes',
                'required',
                new EnumValue(ModelHasPermissionStatus::class, false),
            ],
            'permissions.*.pivot.body.reason' => 'sometimes|required|string',
        ];
    }
}
