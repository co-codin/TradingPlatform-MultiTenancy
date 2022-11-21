<?php

namespace Modules\Brand\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Validation\Rule;
use Modules\Brand\Enums\AllowedDBTables;
use Modules\Brand\Services\BrandDBService;

class BrandDBImportRequest extends BaseFormRequest
{
    public function rules()
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
