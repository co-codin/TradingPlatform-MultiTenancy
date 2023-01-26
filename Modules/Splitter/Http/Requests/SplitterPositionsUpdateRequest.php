<?php

declare(strict_types=1);

namespace Modules\Splitter\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use Modules\Splitter\Models\Splitter;

final class SplitterPositionsUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'splitterids' => 'required|array',
            // 'splitterids.*' => 'required|int|exists:landlord.splitters,id,user_id,' . $this->user()->id,
        ];
    }
}
