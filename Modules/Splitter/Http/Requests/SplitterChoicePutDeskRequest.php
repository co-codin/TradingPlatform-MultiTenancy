<?php

declare(strict_types=1);

namespace Modules\Splitter\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class SplitterChoicePutDeskRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'ids' => 'required|array',
            'ids.*' => 'required|int|exists:tenant.desks,id',
        ];
    }
}
