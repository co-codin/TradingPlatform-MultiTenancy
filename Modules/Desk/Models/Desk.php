<?php

declare(strict_types=1);

namespace Modules\Desk\Models;

use App\Relationships\Traits\WhereHasForTenant;
use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;
use Modules\Customer\Models\Customer;
use Modules\Desk\Database\factories\DeskFactory;
use Modules\Geo\Models\Country;
use Modules\Language\Models\Language;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Desk extends Model
{
    use HasFactory;
    use SoftDeletes;
    use NodeTrait;
    use UsesTenantConnection;
    use WhereHasForTenant;
    use ActivityLog;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return DeskFactory::new();
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

    /**
     * Customers relation.
     *
     * @return HasMany
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Users relation.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToManyTenant(User::class, 'user_desk');
    }
}
