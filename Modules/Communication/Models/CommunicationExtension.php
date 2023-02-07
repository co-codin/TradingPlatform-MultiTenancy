<?php

declare(strict_types=1);

namespace Modules\Communication\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Communication\Database\factories\CommunicationExtensionFactory;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class CommunicationExtension extends Model
{
    use HasFactory;
    use UsesTenantConnection;
    use ActivityLog;

    protected $guarded = ['id'];

    protected static function newFactory(): CommunicationExtensionFactory
    {
        return CommunicationExtensionFactory::new();
    }

    /**
     * Provider relation.
     *
     * @return BelongsTo
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(CommunicationProvider::class);
    }

    /**
     * User relation.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
