<?php

declare(strict_types=1);

namespace Modules\Department\Models;

use App\Relationships\Traits\WhereHasForTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Customer\Models\Customer;
use Modules\Department\Database\factories\DepartmentFactory;
use Modules\Department\Enums\DepartmentEnum;
use Modules\Sale\Models\SaleStatus;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Class Department
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property bool $is_active
 * @property bool $is_default
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
final class Department extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UsesTenantConnection;
    use WhereHasForTenant;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id',
    ];

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): DepartmentFactory
    {
        return DepartmentFactory::new();
    }

    /**
     * Is conversion.
     *
     * @return bool
     */
    public function isConversion(): bool
    {
        return $this->name === DepartmentEnum::CONVERSION;
    }

    /**
     * Is retention.
     *
     * @return bool
     */
    public function isRetention(): bool
    {
        return $this->name === DepartmentEnum::RETENTION;
    }

    /**
     * Customer relation.
     *
     * @return HasMany
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Users relation.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToManyTenant(User::class, 'user_department');
    }

    /**
     * Sale statuses relation.
     *
     * @return HasMany
     */
    public function saleStatuses(): HasMany
    {
        return $this->hasMany(SaleStatus::class);
    }
}
