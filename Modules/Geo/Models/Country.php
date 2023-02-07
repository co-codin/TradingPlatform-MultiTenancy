<?php

declare(strict_types=1);

namespace Modules\Geo\Models;

use App\Relationships\Traits\WhereHasForTenant;
use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Geo\Database\factories\CountryFactory;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

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
    use HasFactory;
    use SoftDeletes;
    use UsesLandlordConnection;
    use WhereHasForTenant;
    use ActivityLog;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id',
    ];

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): CountryFactory
    {
        return CountryFactory::new();
    }

    public function scopeIsForbidden($query)
    {
        return $query->whereIsForbidden(false);
    }
}
