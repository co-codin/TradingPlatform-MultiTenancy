<?php

declare(strict_types=1);

namespace Modules\Communication\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Communication\Database\factories\EmailFactory;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Class Email
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
final class Email extends Model
{
    use SoftDeletes;
    use HasFactory;
    use UsesTenantConnection;
    use ActivityLog;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return EmailFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplates::class, 'email_template_id');
    }

    public function sendemailable(): MorphTo
    {
        return $this->morphTo();
    }

    public function emailable(): MorphTo
    {
        return $this->morphTo();
    }
}
