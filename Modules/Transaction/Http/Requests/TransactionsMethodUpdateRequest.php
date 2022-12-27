<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class TransactionsMethodUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'is_active' => 'sometimes|required|boolean|max:255',
        ];
    }
}
