<?php

namespace Modules\Brand\Models;

use App\Contracts\HasTenantDBConnection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Modules\Brand\Database\factories\BrandFactory;
use Modules\Brand\Events\BrandCreated;
use Modules\User\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $slug
 * @property string $logo_url
 * @property bool $is_active
 * @property array $tables
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Collection|User[] $users
 */
class Brand extends Model implements HasTenantDBConnection
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'tables' => 'array',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dispatchesEvents = [
        'created' => BrandCreated::class,
    ];

    /**
     * Get activity log options.
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly([
                'created_at',
                'updated_at',
            ])
            ->logOnlyDirty();
    }

    /**
     * {@inheritdoc}
     */
    protected static function newFactory()
    {
        return BrandFactory::new();
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
        return $this->belongsToMany(User::class, 'user_brand');
    }
}
