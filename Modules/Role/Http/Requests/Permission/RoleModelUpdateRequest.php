<?php

declare(strict_types=1);

namespace Modules\Role\Http\Requests\Permission;

use App\Http\Requests\BaseFormRequest;

final class RoleModelUpdateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'selected_actions' => 'present|array',
            'selected_actions.*' => 'distinct|string|exists:landlord.actions,name',
            'selected_view_columns' => 'present|array',
            'selected_view_columns.*' => 'distinct|string|exists:landlord.columns,name',
            'selected_edit_columns' => 'present|array',
            'selected_edit_columns.*' => 'distinct|string|exists:landlord.columns,name',
        ];
    }
}
