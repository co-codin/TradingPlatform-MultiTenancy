<?php

declare(strict_types=1);

namespace Modules\Desk\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;
use Modules\Customer\Models\Customer;
use Modules\Desk\Database\factories\DeskFactory;
use Modules\Geo\Models\Country;
use Modules\Language\Models\Language;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Desk extends Model
{
    use HasFactory;
    use SoftDeletes;
    use NodeTrait;
    use LogsActivity;
    use UsesTenantConnection;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return DeskFactory::new();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly([
                'created_at',
                'updated_at',
            ])
            ->logOnlyDirty();
    }

    /**
     * Language relation.
     *
     * @return BelongsToMany
     */
    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'desk_language');
    }

    /**
     * Country relation.
     *
     * @return BelongsToMany
     */
    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'desk_country');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
