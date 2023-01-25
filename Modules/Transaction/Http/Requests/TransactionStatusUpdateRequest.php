<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Transaction\Enums\TransactionStatusEnum;

final class TransactionStatusUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:35',
                new EnumValue(TransactionStatusEnum::class, false),
            ],
            'title' => 'sometimes|required|string|max:35',
            'is_active' => 'sometimes|required|boolean',
            'is_valid' => 'sometimes|required|boolean',
        ];
    }
}
