<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Requests\Customer;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Transaction\Enums\TransactionType;

final class TransactionCreateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'type' => ['required', new EnumValue(TransactionType::class)],
            'amount' => 'required|max:20|decimal:10',
            'customer_id' => 'required|int|exists:tenant.customers,id',
            'method_id' => 'required|int|exists:tenant.transaction_methods,id',

            'external_id' => 'sometimes|required|string|nullable',
            'description' => 'sometimes|required|string|nullable',
            'is_test' => 'sometimes|required|boolean',
        ];
    }
}
