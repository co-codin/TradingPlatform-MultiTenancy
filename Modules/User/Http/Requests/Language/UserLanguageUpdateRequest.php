<?php

namespace Modules\User\Http\Requests\Language;

use App\Http\Requests\BaseFormRequest;

class UserLanguageUpdateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'languages' => [
                'required',
                'array',
            ],
            'languages.*.id' => 'distinct|integer|exists:languages,id',
        ];
    }
}
