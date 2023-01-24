<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class EmailTemplatesCreateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:35',
            'body' => 'required|string',
        ];
    }
}
