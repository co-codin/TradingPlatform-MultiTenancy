<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class EmailSendRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email_id' => 'required|int|exists:tenant.emails,id',
        ];
    }
}
