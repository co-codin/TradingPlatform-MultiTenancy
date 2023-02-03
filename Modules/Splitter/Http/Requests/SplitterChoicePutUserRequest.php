<?php

declare(strict_types=1);

namespace Modules\Splitter\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;
use Modules\Splitter\Enums\SplitterChoiceOptionPerDay;
use Modules\Splitter\Models\SplitterChoice;
use Modules\Splitter\Rules\TotalPercentageRule;

final class SplitterChoicePutUserRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        $splitterChoice = SplitterChoice::find(request('splitter_choice'));

        return [
            'workers' => [
                'required',
                'array',
                new TotalPercentageRule($splitterChoice?->option_per_day == SplitterChoiceOptionPerDay::PERCENT_PER_DAY),
            ],
            'workers.*.id' => 'required|int|exists:landlord.users,id',
            'workers.*.cap_per_day' => [
                Rule::requiredIf(function () use ($splitterChoice) {
                    return ($splitterChoice?->option_per_day == SplitterChoiceOptionPerDay::CAPACITY_PER_DAY) ?? true;
                }),
                'int',
            ],
            'workers.*.percentage_per_day' => [
                Rule::requiredIf(function () use ($splitterChoice) {
                    return ($splitterChoice?->option_per_day == SplitterChoiceOptionPerDay::PERCENT_PER_DAY) ?? true;
                }),
                'int',
            ],
        ];
    }
}
