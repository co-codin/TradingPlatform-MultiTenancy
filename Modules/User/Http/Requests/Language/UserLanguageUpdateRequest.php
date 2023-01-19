<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests\Language;

use App\Http\Requests\BaseFormRequest;

final class UserLanguageUpdateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'languages' => 'present|array',
            'languages.*.id' => 'distinct|integer|exists:landlord.languages,id',
        ];
    }
}
