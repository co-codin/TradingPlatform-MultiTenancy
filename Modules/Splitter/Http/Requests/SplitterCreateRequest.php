<?php

declare(strict_types=1);

namespace Modules\Splitter\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Splitter\Enums\SplitterChoiceOptionPerDay;
use Modules\Splitter\Enums\SplitterChoiceType;

final class SplitterCreateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:35',
            'is_active' => 'required|boolean',
            'conditions' => 'sometimes|array',
            'share_conditions' => 'sometimes|array',
            'splitter_choice' => 'required|array',
            'splitter_choice.type' => [
                'required',
                new EnumValue(SplitterChoiceType::class, false),
            ],
            'splitter_choice.option_per_day' => [
                'required',
                new EnumValue(SplitterChoiceOptionPerDay::class, false),
            ],
        ];
    }
}
