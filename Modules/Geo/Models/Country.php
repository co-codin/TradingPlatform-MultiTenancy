<?php

namespace Modules\Geo\Models;

use App\Models\Traits\ForTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Geo\Database\factories\CountryFactory;

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
    protected static function newFactory(): CountryFactory
    {
        return CountryFactory::new();
    }
}
