<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use App\Enums\RegexValidationEnum;
use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Exception;
use Modules\Customer\Enums\Gender;

final class CustomerCreateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     *
     * @throws Exception
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:35|regex:' . RegexValidationEnum::NAME,
            'last_name' => 'required|string|max:35|regex:' . RegexValidationEnum::NAME,
            'gender' => [
                'sometimes',
                'required',
                new EnumValue(Gender::class, false),
            ],
            'email' => 'required|email|max:100|unique:tenant.customers,email',
            'email_2' => 'sometimes|required|email|max:100',
            'password' => [
                'required',
                'string',
                'regex:' . RegexValidationEnum::PASSWORD,
            ],
            'phone' => [
                'required',
                'string',
                'regex:' . RegexValidationEnum::PHONE,
            ],
            'phone_2' => [
                'sometimes',
                'required',
                'string',
                'regex:' . RegexValidationEnum::PHONE,
            ],
            'language_id' => 'required|int|exists:landlord.languages,id',
            'platform_language_id' => 'sometimes|required|int|exists:landlord.languages,id',
            'browser_language_id' => 'sometimes|required|int|exists:landlord.languages,id',
            'currency_id' => 'required|int|exists:landlord.currencies,id',
            'country_id' => 'required|int|exists:landlord.countries,id',
            'campaign_id' => 'sometimes|required|int|exists:landlord.campaigns,id',
            'desk_id' => 'sometimes|integer|exists:tenant.desks,id',
            'department_id' => 'sometimes|integer|exists:tenant.departments,id',
            'city' => 'sometimes|string|max:35',
            'address' => 'sometimes|string|max:100',
            'postal_code' => 'sometimes|string|max:35',
            'offer_name' => 'sometimes|string|max:35',
            'offer_url' => 'sometimes|string',
            'comment_about_customer' => 'sometimes|string|max:255',
            'source' => 'sometimes|string|max:35',
            'click_id' => 'sometimes|string|max:35',
            'free_param_1' => 'sometimes|string|max:35',
            'free_param_2' => 'sometimes|string|max:35',
            'free_param_3' => 'sometimes|string|max:35',
        ];
    }
}
