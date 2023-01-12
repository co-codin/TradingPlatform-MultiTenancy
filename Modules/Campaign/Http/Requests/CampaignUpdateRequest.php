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
            'cpa' => 'required|numeric',
            'working_hours' => 'required|array',
            'daily_cap' => 'required|integer',
            'crg' => 'required|numeric',
        ];
    }
}
