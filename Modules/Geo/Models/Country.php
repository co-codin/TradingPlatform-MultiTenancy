<?php

declare(strict_types=1);

namespace Modules\Geo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Geo\Database\factories\CountryFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Class Country
 *
 * @property int $id
 * @property string $name
 * @property string $iso2
 * @property string $iso3
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Country extends Model
{
    use HasFactory, SoftDeletes;
    use UsesTenantConnection;

    /**
     * @inheritdoc
     */
    protected $guarded = [
        'id',
    ];

    /**
     * @inheritDoc
     */
    protected static function newFactory(): CountryFactory
    {
        return CountryFactory::new();
    }
}
