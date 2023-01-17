<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class WalletCreateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'mt5_id' => 'required|string|max:255',
            'currency_id' => 'required|integer|max:255|exists:landlord.currencies,id',
            'customer_id' => 'required|integer|max:255|exists:tenant.customers,id',
        ];
    }
}
