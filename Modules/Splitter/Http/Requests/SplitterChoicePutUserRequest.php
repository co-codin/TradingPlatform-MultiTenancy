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
            'workers' => 'required|array',
            'workers.*.id' => 'required|int|exists:landlord.users,id',
            'workers.*.cap_per_day' => 'required|int',
            'workers.*.percentage_per_day' => 'required|int',
        ];
    }
}
