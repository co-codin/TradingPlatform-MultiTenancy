<?php

namespace Modules\Role\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Modules\Brand\Models\Brand;
use Modules\Role\Database\factories\RoleFactory;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;
    use UsesLandlordConnection;

    /**
     * Get permissions by total count attribute.
     *
     * @return string
     */
    public function getAllPermissionsCountAttribute(): string
    {
        $count = Cache::tags(['permissions', 'count'])->get($this->id, fn () => $this->permissions()->count());
        $total = Cache::tags(['permissions', 'count'])->get('total', fn () => Permission::query()->count());

        return "{$count}/{$total}";
    }

    /**
     * Scope get roles without brand and with brands.
     *
     * @param  Builder  $builder
     * @return void
     */
    public function scopeByBrand(Builder $builder): void
    {
        $builder->whereNull('brand_id')
            ->when(
                Tenant::checkCurrent(),
                fn ($q) => $q->orWhere('brand_id', Tenant::current()->id),
            );
    }

    /**
     * {@inheritDoc}
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            'role_id',
            'permission_id'
        );
    }

    /**
     * Brand relation.
     *
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): RoleFactory
    {
        return RoleFactory::new();
    }
}
