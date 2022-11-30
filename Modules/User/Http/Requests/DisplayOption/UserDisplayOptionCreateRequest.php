<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests\DisplayOption;

use App\Http\Requests\BaseFormRequest;

final class UserDisplayOptionCreateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    final public function rules(): array
    {
        return [
            'name' => 'required|string',
            'columns' => 'nullable|array',
            'columns.*' => 'required|string'
        ];
    }
}
