<?php

declare(strict_types=1);

namespace Modules\Communication\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Communication\Database\factories\EmailTemplatesFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Class EmailTemplates
 *
 * @property int $id
 * @property string $name
 * @property string $body
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
final class EmailTemplates extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UsesTenantConnection;
    use ActivityLog;

    /**
     * {@inheritDoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): EmailTemplatesFactory
    {
        return EmailTemplatesFactory::new();
    }
}
