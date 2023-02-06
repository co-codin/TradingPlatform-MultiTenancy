<?php

declare(strict_types=1);

namespace Modules\User\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Database\factories\WorkerInfoFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class WorkerInfo extends Model
{
    use HasFactory;
    use UsesTenantConnection;
    use SoftDeletes;
    use ActivityLog;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id',
    ];

    /**
     * {@inheritdoc}
     */
    protected $table = 'worker_info';

    /**
     * User relation.
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): WorkerInfoFactory
    {
        return WorkerInfoFactory::new();
    }
}
