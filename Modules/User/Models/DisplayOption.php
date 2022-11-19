<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Database\factories\UserDisplayOptionFactory;

class DisplayOption extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    public const AVAILABLE_COLUMNS = [
        'id' => 'id',
        'login' => 'login',
        'first_name' => 'first_name',
        'last_name' => 'last_name',
        'global_id' => 'global_id',
        'global_name' => 'global_name',
        'department' => 'department',
        'shift_management' => 'shift_management',
        'traders_amount' => 'traders_amount',
        'total_deposits' => 'total_deposits',
        'default_voip' => 'default_voip',
        'voip_extensions' => 'voip_extensions',
        'active' => 'active',
    ];

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritDoc}
     */
    protected static function newFactory()
    {
        return UserDisplayOptionFactory::new();
    }
}
