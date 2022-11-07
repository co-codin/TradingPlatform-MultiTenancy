<?php

namespace Modules\Token\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Modules\User\Http\Resources\UserResource;

class TokenResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'user' => new UserResource($this->whenLoaded('user')),
        ]);
    }
}
