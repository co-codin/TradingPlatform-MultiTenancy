<?php

declare(strict_types=1);

namespace Modules\Communication\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Communication\Database\factories\CallFactory;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Class Call
 *
 * @property int $id
 * @property int $email_template_id
 * @property string $subject
 * @property string $body
 * @property bool $sent_by_system
 * @property int|null $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
final class Call extends Model
{
    use SoftDeletes;
    use HasFactory;
    use UsesTenantConnection;
    use ActivityLog;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return CallFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(CommunicationProvider::class);
    }

    public function sendcallable(): MorphTo
    {
        return $this->morphTo();
    }

    public function callable(): MorphTo
    {
        return $this->morphTo();
    }
}
