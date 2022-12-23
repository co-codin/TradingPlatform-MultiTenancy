<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Resources\Chat;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Communication\Models\ChatConversation;
use Modules\User\Http\Resources\AuthUserResource;
use Modules\User\Http\Resources\UserResource;
use OpenApi\Annotations as OA;

class ChatUserResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
        ];
    }
}
