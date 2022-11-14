<?php

namespace Modules\Brand\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Brand\Enums\AllowedDBTables;

class BrandDBCreateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'tables' => [
                'required',
                'array',
                new EnumValue(AllowedDBTables::class, false),
            ],
            'brand_id' => 'required|exists:brands,id',
        ];
    }
}
