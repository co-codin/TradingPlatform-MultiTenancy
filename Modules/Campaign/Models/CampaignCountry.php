<?php

declare(strict_types=1);

namespace Modules\Campaign\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Campaign\Database\factories\CampaignCountryFactory;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

final class CampaignCountry extends Pivot
{
    use HasFactory;
    use UsesLandlordConnection;

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
        'working_hours' => 'collection',
        'working_days' => 'collection',
    ];

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): CampaignCountryFactory
    {
        return CampaignCountryFactory::new();
    }
}
