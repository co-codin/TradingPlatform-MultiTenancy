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
        ];
    }
}