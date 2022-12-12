<?php

declare(strict_types=1);

namespace Modules\Department\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Customer\Models\Customer;
use Modules\Department\Database\factories\DepartmentFactory;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Multitenancy\Models\Tenant;

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
class Department extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UsesTenantConnection;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if ($tenant = Tenant::current()) {
            $this->table = $tenant->getDatabaseName() . '.' . $this->getTable();
        }
    }

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): DepartmentFactory
    {
        return DepartmentFactory::new();
    }

    public function customers()
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
        $tenant = Tenant::current() ? Tenant::current()->getDatabaseName() . '.' : '';

        return $this->belongsToMany(User::class, $tenant . 'user_department');
    }
}
