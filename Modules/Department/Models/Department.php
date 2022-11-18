<?php

namespace Modules\Department\Models;

use App\Models\Traits\ForTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Department\Database\factories\DepartmentFactory;
use Modules\User\Models\User;

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
    use ForTenant, HasFactory, SoftDeletes;

    /**
     * @inheritdoc
     */
    protected $guarded = [
        'id',
    ];

    /**
     * @inheritDoc
     */
    protected static function newFactory(): DepartmentFactory
    {
        return DepartmentFactory::new();
    }

    /**
     * Users relation.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_department');
    }
}
