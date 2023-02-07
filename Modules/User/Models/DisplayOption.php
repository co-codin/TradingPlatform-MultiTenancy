<?php

namespace Modules\User\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Database\factories\UserDisplayOptionFactory;
use Modules\User\Dto\DisplayOptionColumnsDto;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class DisplayOption extends Model
{
    use HasFactory;
    use UsesLandlordConnection;
    use ActivityLog;

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * @var int
     */
    public const DEFAULT_PER_PAGE = 15;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'columns' => DisplayOptionColumnsDto::class,
    ];

    /**
     * Display options relation.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Model relation.
     *
     * @return BelongsTo
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Model::class);
    }

    /**
     * {@inheritDoc}
     */
    protected static function newFactory()
    {
        return UserDisplayOptionFactory::new();
    }
}
