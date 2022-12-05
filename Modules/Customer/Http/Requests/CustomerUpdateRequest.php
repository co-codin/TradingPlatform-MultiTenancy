<?php

namespace Modules\Customer\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Customer\Enums\Gender;
class CustomerUpdateRequest extends BaseFormRequest
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
                new EnumValue(Gender::class, false)
            ],
            'email' => 'required|email|max:100|unique:customers,email',
            'password' => 'sometimes|string',
            'phone' => 'required|string',
            'country_id' => 'sometimes|int|exists:countries,id',
            'phone2' => 'sometimes|',
            'language_id' => 'sometimes|int|exists:languages,id',
            'city' => 'sometimes|string',
            'address' => 'sometimes|string',
            'postal_code' => 'sometimes|string',
            'desk_id' => 'sometimes|integer|exists:desks,id',
            'department_id' => 'sometimes|integer|exists:departments,id',
            'offer_name' => 'sometimes|string',
            'offer_url' => 'sometimes|string',
            'comment_about_customer' => 'sometimes|string',
            'source' => 'sometimes|string',
            'click_id' => 'sometimes|string',
            'free_param_1' => 'sometimes|string',
            'free_param_2' => 'sometimes|string',
            'free_param_3' => 'sometimes|string',
        ];
    }
}
