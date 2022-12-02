<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class CustomerCreateRequest extends BaseFormRequest
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
            'gender' => 'required|int',
            'email' => 'required|email|max:100|unique:customers,email',
            'password' => 'required|string',
            'phone' => 'required|string',
            'country_id' => 'sometimes|int|exists:countries,id',
            'phone2' => 'sometimes|',
            'language_id' => 'sometimes|int',
            'city' => 'sometimes|string',
            'address' => 'sometimes|string',
            'postal_code' => 'sometimes|string',
            'desk_id' => 'sometimes|integer',
            'department_id' => 'sometimes|integer',
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
