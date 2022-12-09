<?php

declare(strict_types=1);

namespace Modules\Sale\Models;

use App\Models\Traits\ForTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Sale\Database\factories\SaleStatusFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Class SaleStatus
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property bool $is_active
 * @property string $color
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class SaleStatus extends Model
{
    // use ForTenant;
    use HasFactory;
    use SoftDeletes;
    use UsesTenantConnection;
    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): SaleStatusFactory
    {
        return SaleStatusFactory::new();
    }
}
