<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class TransactionUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|max:20',
            'method_id' => 'required|int|exists:tenant.transaction_methods,id',
            'is_test' => 'required|boolean',
            'worker_id' => 'required|int|exists:landlord.users,id',
            'status_id' => 'required|int|exists:tenant.transaction_statuses,id',
            'external_id' => 'required|string|nullable',
            'description' => 'required|string|nullable',
        ];
    }
}
