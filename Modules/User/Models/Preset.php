<?php

declare(strict_types=1);

namespace Modules\User\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Database\factories\PresetFactory;
use Modules\User\Dto\PresetColumnsDto;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

final class Preset extends Model
{
    use HasFactory;
    use UsesLandlordConnection;
    use ActivityLog;

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    protected $casts = [
        'columns' => PresetColumnsDto::class,
    ];

    /**
     * User relation.
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
    protected static function newFactory(): PresetFactory
    {
        return PresetFactory::new();
    }
}
