<?php

declare(strict_types=1);

namespace Modules\Campaign\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class CampaignCreateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'affiliate_id' => 'required|int|exists:landlord.users,id',
            'name' => 'required|string|max:35',
            'cpa' => 'required|numeric',
            'working_hours' => 'required|array',
            'daily_cap' => 'required|integer',
            'crg' => 'required|numeric',
            'is_active' => 'required|boolean',
            'phone_verification' => 'sometimes|required|boolean',
            'balance' => 'required|numeric',
            'monthly_cr' => 'required|integer',
            'monthly_pv' => 'required|integer',
            'crg_cost' => 'required|numeric',
            'ftd_cost' => 'required|numeric',
            'countries' => 'sometimes|required|array',
            'countries.*.id' => [
                'required',
                'distinct',
                'integer',
                'min:1',
                'exists:landlord.countries,id',
            ],
            'countries.*.pivot.cpa' => [
                'required',
                'numeric',
                'min:1',
            ],
            'countries.*.pivot.crg' => [
                'required',
                'numeric',
                'min:1',
            ],
            'countries.*.pivot.working_days' => [
                'required',
                'array',
            ],
            'countries.*.pivot.working_hours' => [
                'required',
                'array',
            ],
            'countries.*.pivot.daily_cap' => [
                'required',
                'integer',
            ],
        ];
    }
}
