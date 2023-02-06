<?php

namespace Modules\Brand\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Brand\Database\factories\BrandFactory;
use Modules\Role\Enums\DefaultRole;
use Modules\Splitter\Models\Splitter;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
use Spatie\Multitenancy\Models\Tenant;

/**
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $slug
 * @property string $logo_url
 * @property bool $is_active
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Collection|User[] $users
 */
class Brand extends Tenant
{
    use HasFactory;
    use SoftDeletes;
    use UsesLandlordConnection;
    use ActivityLog;

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * Get admin brand.
     *
     * @return $this|null
     */
    public static function getAdminBrand(): ?self
    {
        return self::query()->where('name', DefaultRole::ADMIN)->first();
    }

    protected static function booted()
    {
        static::creating(fn (Brand $brand) => $brand->createDatabase());
    }

    /**
     * {@inheritdoc}
     */
    protected static function newFactory()
    {
        return BrandFactory::new();
    }

    /**
     * Create and run tenant database migration through tenant db connection instead
     *
     * @return void
     */
    public function createDatabase(): void
    {
        DB::connection($this->tenantDatabaseConnectionName())->statement("CREATE SCHEMA IF NOT EXISTS {$this->database}");
    }

    /**
     * {@inheritDoc}
     */
    public function getTenantConnectionData(): array
    {
        return [
            'search_path' => $this->getTenantSchemaName(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getTenantSchemaName(): string
    {
        return Str::snake(Str::camel($this->slug));
    }

    /**
     * Get users relation.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_brand')
            ->using(UserBrand::class);
    }

    public function splitters(): HasMany
    {
        return $this->hasMany(Splitter::class);
    }
}
