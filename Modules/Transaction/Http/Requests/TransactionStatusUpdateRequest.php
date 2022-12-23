<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class TransactionStatusUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'is_active' => 'sometimes|required|boolean|max:255',
            'is_valid' => 'sometimes|required|boolean|max:255',
        ];
    }
}
