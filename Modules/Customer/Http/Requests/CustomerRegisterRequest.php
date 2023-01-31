<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use App\Enums\RegexValidationEnum;
use App\Http\Requests\BaseFormRequest;
use App\Services\Validation\Phone;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Support\Arr;
use Modules\Campaign\Models\Campaign;
use Modules\Customer\Enums\Gender;
use Modules\Geo\Models\Country;

final class CustomerRegisterRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'first_name' => 'required|string|max:35',
            'last_name' => 'required|string|max:35',
            'gender' => [
                'required',
                new EnumValue(Gender::class, false),
            ],
            'email' => 'required|email|max:100|unique:tenant.customers,email',
            'password' => [
                'required',
                'string',
                'confirmed',
                'regex:'.RegexValidationEnum::PASSWORD,
            ],
            'phone' => [
                'required',
                'string',
                'regex:'.RegexValidationEnum::PHONE,
            ],
            'country_id' => 'required|int|exists:landlord.countries,id',
            'platform_language_id' => 'sometimes|required|int|exists:landlord.languages,id',
            'browser_language_id' => 'sometimes|required|int|exists:landlord.languages,id',
        ];

        $this->validate(Arr::only($rules, ['country_id', 'campaign_id']));

        if (Campaign::query()->find($this->post('campaign_id'))?->phone_verification) {
            $rules['phone'][] = (new Phone)->country(
                Country::query()->find($this->post('country_id'))
            );
        }

        return $rules;
    }

    public function validated($key = null, $default = null)
    {
        if ($key === 'email') {
            return $this->input('email', $default);
        }

        if ($key) {
            return parent::validated($key, $default);
        }

        return array_merge(parent::validated(), ['email' => $this->input('email')]);
    }

    final protected function passedValidation(): void
    {
        parent::passedValidation();
        $this->merge([
            'email' => strtolower($this->input('email')),
        ]);
    }
}
