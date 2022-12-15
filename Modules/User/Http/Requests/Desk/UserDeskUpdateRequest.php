<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests\Desk;

use App\Http\Requests\BaseFormRequest;

final class UserDeskUpdateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'desks' => [
                'required',
                'array',
            ],
            'desks.*.id' => 'distinct|integer|exists:tenant.desks,id',
        ];
    }
}
