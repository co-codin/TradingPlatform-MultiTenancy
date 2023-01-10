<?php

declare(strict_types=1);

namespace Modules\Role\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\ModelHasPermission;
use Spatie\Permission\Traits\HasRoles as SpatieHasRoles;

trait HasRoles
{
    use SpatieHasRoles {
        permissions as spatiePermissions;
    }

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
     * Is affiliate.
     *
     * @return bool
     */
    public function isAffiliate(): bool
    {
        return $this->hasRole(DefaultRole::AFFILIATE);
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

    /**
     * {@inheritDoc}
     */
    public function permissions(): BelongsToMany
    {
        return $this->spatiePermissions()
            ->using(ModelHasPermission::class)
            ->withPivot([
                'status',
                'data',
            ]);
    }
}
