<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests\Affiliate;

use App\Enums\RegexValidationEnum;
use App\Http\Requests\BaseFormRequest;

final class CustomerCreateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:35|regex:'.RegexValidationEnum::NAME,
            'last_name' => 'required|string|max:35|regex:'.RegexValidationEnum::NAME,
            'phone' => 'required|string|regex:'.RegexValidationEnum::PHONE,
            'phone_2' => 'sometimes|required|string|regex:'.RegexValidationEnum::PHONE,
            'email' => 'required|email|max:100|unique:tenant.customers,email',
            'email_2' => 'sometimes|required|email|max:100',
            'country' => 'required|string|max:35',
            'language' => 'required|string|max:35',
            'currency' => 'required|string|max:35',
            'campaign_id' => 'required|integer|exists:landlord.campaigns,id',
            'tenant' => 'required|string|max:35|exists:landlord.brands,domain',
            'desk_id' => 'sometimes|integer|exists:tenant.desks,id',
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
