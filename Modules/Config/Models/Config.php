<?php

declare(strict_types=1);

namespace Modules\Config\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Config\Database\factories\ConfigFactory;
use Modules\Config\Dto\ConfigValue;
use Modules\Config\Enums\ConfigDataTypeEnum;
use Modules\Config\Enums\ConfigEnum;
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
    use ActivityLog;

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
        return $this->data_type === ConfigDataTypeEnum::JSON;
    }

    /**
     * Is string data type.
     *
     * @return bool
     */
    public function isStringDataType(): bool
    {
        return $this->data_type === ConfigDataTypeEnum::STRING;
    }

    /**
     * Is integer data type.
     *
     * @return bool
     */
    public function isIntegerDataType(): bool
    {
        return $this->data_type === ConfigDataTypeEnum::INTEGER;
    }

    /**
     * Config type relation.
     *
     * @return BelongsTo
     */
    public function configType(): BelongsTo
    {
        return $this->belongsTo(ConfigType::class);
    }

    /**
     * Factory.
     *
     * @return ConfigFactory
     */
    protected static function newFactory(): Factory
    {
        return ConfigFactory::new();
    }

    public static function getValueByEnum(ConfigEnum $configEnum): mixed
    {
        return self::firstWhere('name', $configEnum->value)?->value;
    }
}
