<?php

declare(strict_types=1);

namespace Modules\Role\Http\Requests\Column;

use App\Http\Requests\BaseFormRequest;

final class ColumnUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'name' => "sometimes|required|string|max:255|unique:public.columns,name,{$this->route('permissions_column')}",
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
