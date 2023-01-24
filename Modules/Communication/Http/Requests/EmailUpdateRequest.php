<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class EmailUpdateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email_template_id' => 'required|int|exists:tenant.email_templates,id',
            'subject' => 'required|string',
            'body' => 'required|string',
            'sent_by_system' => 'sometimes|boolean',
        ];
    }
}
