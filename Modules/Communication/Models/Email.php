<?php

declare(strict_types=1);

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Communication\Database\factories\EmailFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Modules\Communication\Models\EmailTemplates;
use Modules\User\Models\User;

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
    use HasFactory;
    use UsesTenantConnection;

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
}
