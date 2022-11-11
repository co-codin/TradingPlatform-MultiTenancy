<?php

namespace Modules\Geo\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Geo\Enums\CountryPermission;

class CountryIndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'paginate' => 'filled|bool',
            'page' => 'filled|int',
            'per_page' => 'filled|int',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
        return (bool) auth()->user()?->can(CountryPermission::VIEW_COUNTRIES);
    }
}
