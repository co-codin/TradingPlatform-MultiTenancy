<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests\Affiliate;

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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email|max:100|unique:tenant.customers,email',
            'country' => 'required|string',
            'language' => 'required|string',
            'currency' => 'required|string',
            'campaign_id' => 'required|integer|exists:tenant.campaigns,id',
            'brand_id' => 'sometimes|integer|exists:landlord.brand,id',
            'desk_id' => 'sometimes|integer|exists:tenant.desks,id',
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
