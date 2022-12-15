<?php

declare(strict_types=1);

namespace Modules\Role\Http\Requests\Column;

use App\Http\Requests\BaseFormRequest;

final class PermissionColumnUpdateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'columns' => [
                'required',
                'array',
            ],
            'columns.*.id' => 'distinct|integer|exists:public.columns,id',
        ];
    }
}
