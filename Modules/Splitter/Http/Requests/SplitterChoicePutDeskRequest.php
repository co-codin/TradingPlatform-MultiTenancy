<?php

declare(strict_types=1);

namespace Modules\Splitter\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;
use Modules\Splitter\Enums\SplitterChoiceOptionPerDay;
use Modules\Splitter\Models\SplitterChoice;
use Modules\Splitter\Rules\TotalPercentageRule;

final class SplitterChoicePutDeskRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        $splitterChoice = SplitterChoice::find(request('splitter_choice'));

        return [
            'desks' => [
                'required',
                'array',
                new TotalPercentageRule($splitterChoice?->option_per_day == SplitterChoiceOptionPerDay::PERCENT_PER_DAY),
            ],
            'desks.*.id' => 'required|int|exists:tenant.desks,id',
            'desks.*.cap_per_day' => [
                Rule::requiredIf(function () use ($splitterChoice) {
                    return ($splitterChoice?->option_per_day == SplitterChoiceOptionPerDay::CAPACITY_PER_DAY) ?? true;
                }),
                'int',
            ],
            'desks.*.percentage_per_day' => [
                Rule::requiredIf(function () use ($splitterChoice) {
                    return ($splitterChoice?->option_per_day == SplitterChoiceOptionPerDay::PERCENT_PER_DAY) ?? true;
                }),
                'int',
            ],
        ];
    }
}
