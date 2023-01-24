<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class WalletUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:35',
            'title' => 'required|string|max:35',
            'mt5_id' => 'required|string|max:35',
            'currency_id' => 'required|integer|exists:landlord.currencies,id',
            'customer_id' => 'required|integer|exists:tenant.customers,id',
        ];
    }
}
