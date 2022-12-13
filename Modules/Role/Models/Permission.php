<?php

declare(strict_types=1);

namespace Modules\Role\Models;

use App\Models\Action;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Role\Database\factories\PermissionFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Permission\Models\Permission as SpatiePermission;

final class Permission extends SpatiePermission
{
    use HasFactory;
    use UsesTenantConnection;

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): PermissionFactory
    {
        return PermissionFactory::new();
    }

    /**
     * {@inheritDoc}
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_has_permissions'),
            'permission_id',
            'role_id'
        );
    }

    /**
     * Model relation.
     *
     * @return BelongsTo
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(Model::class);
    }

    /**
     * Action relation.
     *
     * @return BelongsTo
     */
    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class);
    }

    /**
     * Column relation.
     *
     * @return BelongsToMany
     */
    public function columns(): BelongsToMany
    {
        return $this->belongsToMany(Column::class, 'permission_column');
    }
}
