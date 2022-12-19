<?php

declare(strict_types=1);

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Modules\Communication\Database\factories\EmailTemplatesFactory;

/**
 * Class Email
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
    use UsesTenantConnection;

    protected $fillable = ['id'];

    protected static function newFactory()
    {
        return EmailTemplatesFactory::new();
    }
}
