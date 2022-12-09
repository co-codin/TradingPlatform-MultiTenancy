<?php

declare(strict_types=1);

namespace Modules\Campaign\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Campaign extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Campaign\Database\factories\CampaignFactory::new();
    }
}
