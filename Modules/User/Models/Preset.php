<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Database\factories\PresetFactory;

class Preset extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

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

    protected static function newFactory()
    {
        return PresetFactory::new();
    }
}
