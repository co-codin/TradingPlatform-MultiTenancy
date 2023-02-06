<?php

declare(strict_types=1);

namespace Modules\Customer\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Customer\Database\factories\CustomerChatMessageFactory;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Class CustomerChatMessage
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
final class CustomerChatMessage extends Model
{
    use HasFactory;
    use UsesTenantConnection;
    use ActivityLog;

    protected $table = 'chat_messages';

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return CustomerChatMessageFactory::new();
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
