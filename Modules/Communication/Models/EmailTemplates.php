<?php

declare(strict_types=1);

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Modules\Communication\Database\factories\EmailTemplatesFactory;

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
