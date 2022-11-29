<?php

declare(strict_types=1);

namespace Modules\Role\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Role\Database\factories\PermissionFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

final class Permission extends SpatiePermission
{
    use HasFactory;

    protected static function newFactory(): PermissionFactory
    {
        return PermissionFactory::new();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_has_permissions'),
            'permission_id',
            'role_id'
        );
    }

    public function model()
    {
        return $this->belongsTo(Model::class);
    }

    public function action()
    {
        return $this->belongsTo(Action::class);
    }

    public function columns(): BelongsToMany
    {
        return $this->belongsToMany(Column::class, 'permission_column');
    }
}
