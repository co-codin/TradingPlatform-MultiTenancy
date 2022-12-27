<?php

declare(strict_types=1);

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\DatabaseNotification as BaseDatabaseNotification;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class DatabaseNotification extends BaseDatabaseNotification
{
    use UsesTenantConnection;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
