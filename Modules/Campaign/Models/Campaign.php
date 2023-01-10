<?php

declare(strict_types=1);

namespace Modules\Campaign\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Campaign\Database\factories\CampaignFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class Campaign extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id',
    ];

    /**
     * {@inheritDoc}
     */
    protected static function newFactory()
    {
        return CampaignFactory::new();
    }
}
