<?php

declare(strict_types=1);

namespace Modules\Campaign\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class CampaignUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
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
            'countries' => 'sometimes|required',
            'countries.*.id' => 'sometimes|required|distinct|integer|min:1|exists:landlord.countries,id',
            'countries.*.pivot.cpa' => 'sometimes|required|numeric',
            'countries.*.pivot.crg' => 'sometimes|required|numeric',
            'countries.*.pivot.working_days' => 'sometimes|required|array',
            'countries.*.pivot.working_hours' => 'sometimes|required|array',
            'countries.*.pivot.daily_cap' => 'sometimes|required|integer',
        ];
    }
}
