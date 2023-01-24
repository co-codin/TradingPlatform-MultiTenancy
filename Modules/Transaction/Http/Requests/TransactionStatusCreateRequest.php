<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Transaction\Enums\TransactionStatusEnum;

final class TransactionStatusCreateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:35',
                new EnumValue(TransactionStatusEnum::class, false),
            ],
            'title' => 'required|string|max:35',
            'is_active' => 'sometimes|required|boolean',
            'is_valid' => 'sometimes|required|boolean',
        ];
    }
}
