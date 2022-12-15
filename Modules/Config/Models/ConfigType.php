<?php

declare(strict_types=1);

namespace Modules\Config\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Config\Database\factories\ConfigTypeFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Config type model.
 *
 * @property int $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property Collection|Config[] $configs
 */
final class ConfigType extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * Configs relation.
     *
     * @return HasMany
     */
    final public function configs(): HasMany
    {
        return $this->hasMany(Config::class);
    }

    /**
     * Factory.
     *
     * @return Factory
     */
    final protected static function newFactory(): Factory
    {
        return ConfigTypeFactory::new();
    }
}
