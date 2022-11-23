<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;
use Modules\Brand\Services\BrandDBService;

final class BrandDBImportRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    final public function rules(): array
    {
        return [
            'modules' => [
                'required',
                'array',
                Rule::in(BrandDBService::ALLOWED_MODULES),
            ],
        ];
    }
}
