<?php

declare(strict_types=1);

namespace Modules\Role\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Modules\Role\Enums\DefaultRole;
use Spatie\Permission\Traits\HasRoles as SpatieHasRoles;

trait HasRoles
{
    use SpatieHasRoles;

    /**
     * Is admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(DefaultRole::ADMIN);
    }

    /**
     * Get admin.
     *
     * @return Model|null
     */
    public static function getAdmin(): ?self
    {
        return self::whereHas('roles', fn ($q) => $q->where('name', DefaultRole::ADMIN))->first();
    }
}
