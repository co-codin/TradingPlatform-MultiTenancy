<?php


namespace Modules\Worker\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Role\Http\Resources\RoleResource;
use Modules\Worker\Models\User;

/**
 * Class AuthUserResource
 * @package Modules\User\Http\Resources
 * @mixin User
 */
class AuthWorkerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'permissions' => $this->getPermissionsViaRoles()->pluck('name'),
            'role' => new RoleResource($this->role),
        ];
    }
}
