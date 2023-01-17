<?php

declare(strict_types=1);

namespace Modules\Language\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class LanguageUpdateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => "sometimes|required|string|unique:landlord.languages,name,{$this->route('language')}",
            'code' => "sometimes|required|string|unique:landlord.languages,code,{$this->route('language')}",
        ];
    }
}
