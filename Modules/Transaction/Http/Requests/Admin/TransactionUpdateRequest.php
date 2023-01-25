<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Transaction\Enums\TransactionStatusEnum;

final class TransactionUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'status' => ['required', new EnumValue(TransactionStatusEnum::class)],
            'amount' => 'required|max:20|decimal:10',
            'method_id' => 'required|int|exists:tenant.transaction_methods,id',
            'worker_id' => 'required|int|exists:landlord.users,id',

            'is_test' => 'sometimes|required|boolean',
            'external_id' => 'sometimes|required|string|nullable',
            'description' => 'sometimes|required|string|nullable',
        ];
    }
}
