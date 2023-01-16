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
            'name' => 'required|string',
            'cpa' => 'required|numeric',
            'working_hours' => 'required|array',
            'daily_cap' => 'required|integer',
            'crg' => 'required|numeric',
            'is_active' => 'required|boolean',
            'balance' => 'required|numeric',
            'monthly_cr' => 'required|integer',
            'monthly_pv' => 'required|integer',
            'crg_cost' => 'required|numeric',
            'ftd_cost' => 'required|numeric',
            'country_id' => 'required|int|exists:landlord.countries,id',
        ];
    }
}
