<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Communication\Enums\NotifiableType;

final class NotificationSendRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'notifiable_id' => 'required|int',
            'notifiable_type' => ['required', new EnumValue(NotifiableType::class)],
            'subject' => 'required|string|max:35',
            'text' => 'required|string',
            'immediately' => 'sometimes|required|boolean',
        ];
    }
}
