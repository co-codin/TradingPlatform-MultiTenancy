<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class CommentUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|required|integer|exists:landlord.users,id',
            'customer_id' => 'sometimes|required|integer|exists:tenant.customers,id',
            'attachments' => 'array',
            'attachments.*' => 'file|max:10000',
            'body' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|required|integer',
        ];
    }
}
