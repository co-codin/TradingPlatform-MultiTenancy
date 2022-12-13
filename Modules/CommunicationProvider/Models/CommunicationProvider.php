<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\CommunicationProvider\Database\factories\CommunicationProviderFactory;
use Modules\User\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

final class CommunicationProvider extends Model
{
    use HasFactory;
    use LogsActivity;
    use UsesLandlordConnection;

    protected $guarded = ['id'];

    protected static function newFactory(): CommunicationProviderFactory
    {
        return CommunicationProviderFactory::new();
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
     * Users relation.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
