<?php

declare(strict_types=1);

namespace Modules\Campaign\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Campaign\Database\factories\CampaignFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Campaign extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    protected $guarded = ['id'];

    protected $casts = [
        'working_hours' => 'array',
    ];

    protected static function newFactory()
    {
        return CampaignFactory::new();
    }
}
