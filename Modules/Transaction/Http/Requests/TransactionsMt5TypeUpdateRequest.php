<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class TransactionsMt5TypeUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:35',
            'title' => 'required|string|max:35',
            'mt5_id' => 'required|string|max:35',
        ];
    }
}
