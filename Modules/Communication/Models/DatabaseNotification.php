<?php

declare(strict_types=1);

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\DatabaseNotification as BaseDatabaseNotification;
use Modules\Communication\Database\factories\NotificationFactory;
use Modules\User\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class DatabaseNotification extends BaseDatabaseNotification
{
    use UsesTenantConnection;

    private static function newFactory(): NotificationFactory
    {
        return NotificationFactory::new();
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
