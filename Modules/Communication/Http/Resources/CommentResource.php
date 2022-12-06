<?php

namespace Modules\Communication\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Modules\User\Http\Resources\UserResource;

class CommentResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'user' => UserResource::collection($this->whenLoaded('roles')),
        ]);
    }
}
