<?php

declare(strict_types=1);

namespace Modules\Language\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class LanguageStoreRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:landlord.languages,name',
            'code' => 'required|string|unique:landlord.languages,code',
        ];
    }
}
