<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Database\factories\UserDisplayOptionFactory;

class DisplayOption extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'columns' => 'array',
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

    protected static function newFactory()
    {
        return UserDisplayOptionFactory::new();
    }
}
