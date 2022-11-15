<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests;

use Illuminate\Support\Arr;

final class UserUpdateBatchRequest extends UserUpdateRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge(
            Arr::prependKeysWith(parent::rules(), 'users.*.'),
            [
                'users' => 'required|array',
            ],
        );
    }
}
