<?php

declare(strict_types=1);

namespace Modules\Campaign\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Campaign\Enums\CampaignTransactionType;

final class CampaignTransactionUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'type' => [
                'required',
                new EnumValue(CampaignTransactionType::class, false),
            ],
            'amount' => 'required|numeric',
            'customer_ids' => 'required|array',
            'customer_ids.*' => 'required|int|exists:tenant.customers,id',
        ];
    }
}
