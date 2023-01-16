<?php

namespace Modules\Campaign\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Campaign\Database\factories\CampaignTransactionFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class CampaignTransaction extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    protected $guarded = [
        'id',
    ];
    protected $casts = [
        'customer_ids' => 'collection',
    ];
    protected static function newFactory()
    {
        return CampaignTransactionFactory::new();
    }
}
