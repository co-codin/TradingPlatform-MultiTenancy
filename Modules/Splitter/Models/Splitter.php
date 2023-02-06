<?php

declare(strict_types=1);

namespace Modules\Splitter\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Splitter\Database\factories\SplitterFactory;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
use Spatie\Multitenancy\Models\Tenant;

final class Splitter extends Model
{
    use HasFactory;
    use UsesLandlordConnection;
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
    protected $casts = [
        'conditions' => 'collection',
        'share_conditions' => 'collection',
    ];

    protected static function newFactory()
    {
        return SplitterFactory::new();
    }

    public function splitterChoice()
    {
        return $this->hasOne(SplitterChoice::class);
    }

    public function scopeCurrentBrand($query)
    {
        return $query->whereBrandId(Tenant::current()?->id);
    }
}
