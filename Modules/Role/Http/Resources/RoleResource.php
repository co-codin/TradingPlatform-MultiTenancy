<?php

namespace Modules\Role\Http\Resources;

use App\Http\Resources\BaseJsonResource;

class RoleResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
        ]);
    }
}
