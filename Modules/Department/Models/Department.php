<?php

namespace Modules\Department\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Department\Database\factories\DepartmentFactory;
use Modules\User\Models\User;

/**
 * Class Department
 *
 * @package Modules\Department\Models
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

    /**
     * @inheritdoc
     */
    protected $guarded = [
        'id',
    ];

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'name',
        'title',
        'is_active',
        'is_default',
    ];

    /**
     * @inheritdoc
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
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
