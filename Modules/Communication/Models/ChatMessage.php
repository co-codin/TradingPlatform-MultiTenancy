<?php

declare(strict_types=1);

namespace Modules\Communication\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Communication\Database\factories\ChatMessageFactory;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Class ChatMessage
 *
 * @property int $id
 * @property int $user_id
 * @property int $customer_id
 * @property string $message
 * @property int $initiator_id
 * @property string $initiator_type
 * @property int $read
 * @property string $created_at
 * @property string $updated_at
 */
final class ChatMessage extends Model
{
    use HasFactory;
    use UsesTenantConnection;
    use ActivityLog;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return ChatMessageFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
