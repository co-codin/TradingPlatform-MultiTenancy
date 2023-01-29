<?php

declare(strict_types=1);

namespace Modules\Splitter\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class SplitterChoicePutUserRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'ids' => 'required|array',
            'ids.*' => 'required|int|exists:users,id',
        ];
    }
}
