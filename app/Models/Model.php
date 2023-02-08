<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Logs\Traits\ActivityLog;
use Database\Factories\ModelFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Modules\Role\Models\Permission;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

final class Model extends BaseModel
{
    use HasFactory;
    use UsesLandlordConnection;
    use ActivityLog;

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): Factory
    {
        return ModelFactory::new();
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

    public function getShortNameAttribute(): string
    {
        return explode('\\', $this->name)[array_key_last(explode('\\', $this->name))];
    }

    /**
     * Permissions relation.
     *
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }
}
