<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Transaction\Enums\TransactionStatusEnum;

final class TransactionUpdateBatchRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'transactions' => 'required',
            'transactions.*.id' => 'required|integer|exists:tenant.transactions,id',
            'transactions.*.status' => ['sometimes', new EnumValue(TransactionStatusEnum::class)],
            'transactions.*.worker_id' => 'sometimes|int|exists:landlord.users,id',
            'transactions.*.is_test' => 'sometimes|required|boolean',
            'transactions.*.method_id' => 'sometimes|int|exists:tenant.transaction_methods,id',
        ];
    }
}
