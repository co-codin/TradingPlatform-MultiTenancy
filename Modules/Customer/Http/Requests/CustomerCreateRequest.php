<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use App\Enums\RegexValidationEnum;
use App\Http\Requests\BaseFormRequest;
use App\Services\Validation\Phone;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Campaign\Models\Campaign;
use Modules\Customer\Enums\Gender;
use Modules\Geo\Models\Country;

final class CustomerCreateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'first_name' => 'required|string|regex:'.RegexValidationEnum::fromValue(RegexValidationEnum::FIRSTNAME)->value,
            'last_name' => 'required|string|regex:'.RegexValidationEnum::fromValue(RegexValidationEnum::LASTNAME)->value,
            'gender' => [
                'required',
                new EnumValue(Gender::class, false),
            ],
            'email' => 'required|email|max:100|unique:tenant.customers,email',
            'password' => 'required|string|regex:'.RegexValidationEnum::fromValue(RegexValidationEnum::PASSWORD)->value,
            'phone' => [
                'required',
                'string',
            ],
            'phone2' => [
                'sometimes',
                'required',
                'string',
            ],
            'language_id' => 'required|int|exists:landlord.languages,id',
            'platform_language_id' => 'sometimes|required|int|exists:landlord.languages,id',
            'browser_language_id' => 'sometimes|required|int|exists:landlord.languages,id',
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
            'currency_id' => 'required|int|exists:landlord.currencies,id',
        ];

        $this->validate([
            'country_id' => 'required|int|exists:landlord.countries,id',
            'campaign_id' => 'sometimes|required|int|exists:landlord.campaigns,id',
        ]);

        $campaign = Campaign::query()->find($this->post('campaign_id'));
        $country = Country::query()->find($this->post('country_id'));

        if ($campaign?->phone_verification) {
            $phoneRule = (new Phone())->country($country);

            $rules['phone'][] = $phoneRule;
            $rules['phone2'][] = $phoneRule;
        }

        return $rules;
    }
}
