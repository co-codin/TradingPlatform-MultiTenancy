<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Transaction\Enums\TransactionStatusName;

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
                'max:255',
                new EnumValue(TransactionStatusName::class, false),
            ],
            'title' => 'required|string|max:255',
            'is_active' => 'sometimes|required|boolean|max:255',
            'is_valid' => 'sometimes|required|boolean|max:255',
        ];
    }
}
