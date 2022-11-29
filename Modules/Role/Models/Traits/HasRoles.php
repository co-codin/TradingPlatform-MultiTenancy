<?php

declare(strict_types=1);

namespace Modules\Role\Models\Traits;

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
    final public function isAdmin(): bool
    {
        return $this->hasRole(DefaultRole::ADMIN);
    }
}
