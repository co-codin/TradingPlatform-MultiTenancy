<?php

declare(strict_types=1);

namespace Modules\Role\Models;

use App\Services\Logs\Traits\ActivityLog;
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

final class Role extends SpatieRole
{
    use HasFactory;
    use UsesLandlordConnection;
    use ActivityLog;

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): RoleFactory
    {
        return RoleFactory::new();
    }

    /**
     * Get permissions by total count attribute.
     *
     * @return string
     */
    public function getPermissionsByTotalCountAttribute(): string
    {
        $count = Cache::tags([self::class, 'permissions', 'count'])
            ->remember($this->id, null, fn () => $this->permissions()->count());

        $total = Cache::tags([self::class, 'permissions', 'count'])
            ->remember('total', null, fn () => Permission::query()->count());

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
        $permissions = $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            'role_id',
            'permission_id'
        )->where('guard_name', 'web');
        Brand::checkCurrent()
            ? $permissions->wherePivot('brand_id', Brand::current()->id)
            : $permissions->wherePivotNull('brand_id');

        return $permissions;
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

    public function columns(): BelongsToMany
    {
        return $this->belongsToMany(Column::class, 'permission_column');
    }

    public function columnsByPermission(int $id): BelongsToMany
    {
        return $this->columns()->wherePivot('permission_id', $id);
    }
}
