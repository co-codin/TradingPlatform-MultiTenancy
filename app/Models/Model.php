<?php

declare(strict_types=1);

namespace App\Models;

use database\factories\ModelFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Role\Models\Permission;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

final class Model extends BaseModel
{
    use HasFactory;
    use UsesLandlordConnection;

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * Permissions relation.
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
        return ModelFactory::new();
    }
}
