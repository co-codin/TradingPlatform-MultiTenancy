<?php

declare(strict_types=1);

namespace Modules\Communication\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Communication\Database\factories\NotificationTemplateFactory;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class NotificationTemplate extends Model
{
    use HasFactory;
    use UsesTenantConnection;
    use ActivityLog;

    protected $casts = [
        'data' => 'array',
    ];

    private static function newFactory(): NotificationTemplateFactory
    {
        return NotificationTemplateFactory::new();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
