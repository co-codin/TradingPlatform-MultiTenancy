<?php

declare(strict_types=1);

namespace Modules\Splitter\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use Modules\Splitter\Rules\ConditionsRule;

final class SplitterUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:35',
            'is_active' => 'required|boolean',
            'conditions' => [
                'sometimes',
                'array',
                new ConditionsRule(),
            ],
            'share_conditions' => 'sometimes|array',
        ];
    }
}
