<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Customer\Enums\Gender;

final class CustomerRegisterRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => [
                'required',
                new EnumValue(Gender::class, false),
            ],
            'email' => 'required|email|max:100|unique:tenant.customers,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|regex:/^\+(?:\d\s?){6,14}\d$/',
            'country_id' => 'required|int|exists:landlord.countries,id',
        ];
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
