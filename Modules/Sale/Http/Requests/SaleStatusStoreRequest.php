<?php

declare(strict_types=1);

namespace Modules\Sale\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class SaleStatusStoreRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'title' => 'required|string',
            'department_id' => 'required|int|exists:tenant.departments,id',
            'color' => [
                'required',
                'string',
                'regex:/^(#[a-zA-Z0-9]{6})$/i',
            ],
        ];
    }
}
