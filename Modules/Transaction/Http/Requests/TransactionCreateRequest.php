<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Validation\Rule;
use Modules\Transaction\Enums\TransactionMt5TypeEnum;
use Modules\Transaction\Enums\TransactionStatusEnum;
use Modules\Transaction\Enums\TransactionType;

final class TransactionCreateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        $rules = [
            'type' => ['required', new EnumValue(TransactionType::class)],
            'mt5_type' => ['required', new EnumValue(TransactionMt5TypeEnum::class)],
            'status' => ['required', new EnumValue(TransactionStatusEnum::class)],
            'amount' => 'required|max:20|decimal:10',
            'customer_id' => 'required|int|exists:tenant.customers,id',
            'method_id' => 'required|int|exists:tenant.transaction_methods,id',
            'wallet_id' => 'required|int|exists:tenant.wallets,id',

            'external_id' => 'sometimes|required|string|nullable',
            'description' => 'sometimes|required|string|nullable',
            'is_test' => 'sometimes|required|boolean',
        ];

        if (
            ($type = TransactionType::coerce($this->input('type')))
            && $type->is(TransactionType::WITHDRAWAL)
        ) {
            $rules['mt5_type'] = [
                'required',
                Rule::in([TransactionMt5TypeEnum::BALANCE, TransactionMt5TypeEnum::CREDIT]),
            ];
        }

        return $rules;
    }
}
