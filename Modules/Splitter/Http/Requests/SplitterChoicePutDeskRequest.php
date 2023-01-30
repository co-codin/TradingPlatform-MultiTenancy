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
            'desks' => 'required|array',
            'desks.*.id' => 'required|int|exists:tenant.desks,id',
            'desks.*.cap_per_day' => 'required|int',
            'desks.*.percentage_per_day' => 'required|int',
        ];
    }
}
