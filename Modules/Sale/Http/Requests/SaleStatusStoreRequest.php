<?php

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
            'color' => 'required|string',
        ];
    }
}
