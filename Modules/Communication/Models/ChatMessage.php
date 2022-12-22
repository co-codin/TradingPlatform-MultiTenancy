<?php

declare(strict_types=1);

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Communication\Database\factories\ChatMessageFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Class ChatMessage
 *
 * @property int $id
 * @property int $user_id
 * @property int $customer_id
 * @property string $message
 * @property int $initiator
 * @property string $created_at
 * @property string $updated_at
 */
final class ChatMessage extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return ChatMessageFactory::new();
    }
}
