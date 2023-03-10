<?php

declare(strict_types=1);

namespace Modules\Role\Http\Requests\Column;

use App\Http\Requests\BaseFormRequest;

final class ColumnCreateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:35|unique:landlord.columns,name',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributes(): array
    {
        return [
            'name' => 'Column name',
        ];
    }
}
