<?php

declare(strict_types=1);

namespace Modules\Config\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Config\Database\factories\ConfigFactory;
use Modules\Config\Dto\ConfigValue;
use Modules\Config\Enums\DataType;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Config model.
 *
 * @property int $id
 * @property int $config_type_id
 * @property string $data_type
 * @property string $name
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 * @property ConfigType $configType
 */
final class Config extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'value' => ConfigValue::class,
    ];

    /**
     * Is json data type.
     *
     * @return bool
     */
    public function isJsonDataType(): bool
    {
        return $this->data_type === DataType::JSON;
    }

    /**
     * Is string data type.
     *
     * @return bool
     */
    public function isStringDataType(): bool
    {
        return $this->data_type === DataType::STRING;
    }

    /**
     * Config type relation.
     *
     * @return BelongsTo
     */
    final public function configType(): BelongsTo
    {
        return $this->belongsTo(ConfigType::class);
    }

    /**
     * Factory.
     *
     * @return ConfigFactory
     */
    final protected static function newFactory(): Factory
    {
        return ConfigFactory::new();
    }
}
