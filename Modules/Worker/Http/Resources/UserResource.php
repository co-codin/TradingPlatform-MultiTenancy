<?php
declare(strict_types=1);


namespace Modules\Worker\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Role\Http\Resources\RoleResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
        ]);
    }
}
