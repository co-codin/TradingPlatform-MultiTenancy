<?php

declare(strict_types=1);

namespace App\Models;

use database\factories\ActionFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Role\Models\Permission;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

final class Action extends Model
{
    use HasFactory;
    use UsesLandlordConnection;

    /**
     * @var array
     */
    public const NAMES = [
        'viewAny' => 'viewAny',
        'view' => 'view',
        'create' => 'create',
        'edit' => 'edit',
        'delete' => 'delete',
        'impersonate' => 'impersonate',
        'export' => 'export',
        'import' => 'import',
        'ban' => 'ban',
    ];

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * Permission relation.
     *
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): Factory
    {
        return ActionFactory::new();
    }
}
