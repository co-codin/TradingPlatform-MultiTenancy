<?php

declare(strict_types=1);

namespace Modules\TelephonyProvider\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use TelephonyProviderFactory;

final class TelephonyExtension extends Model
{
    use HasFactory;
    use LogsActivity;
    use UsesTenantConnection;

    protected $guarded = ['id'];

    protected static function newFactory(): TelephonyProviderFactory
    {
        return TelephonyProviderFactory::new();
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
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
