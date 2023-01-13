<?php

declare(strict_types=1);

namespace Modules\Campaign\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Campaign\Database\factories\CampaignFactory;
use Modules\Geo\Models\Country;
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
     * {@inheritdoc}
     */
    protected $casts = [
        'working_hours' => 'collection',
    ];

    /**
     * {@inheritDoc}
     */
    protected static function newFactory()
    {
        return CampaignFactory::new();
    }

    /**
     * Check for working hours
     *
     * @return bool
     */
    public function isWorkingHours(): bool
    {
        $dayOfTheWeek = Carbon::now()->dayOfWeek;

        if (isset($this->working_hours[$dayOfTheWeek])) {
            $working_hours = $this->working_hours[$dayOfTheWeek];

            $startDate = Carbon::createFromFormat('H:i', $working_hours['start'] ?? '10:00');
            $endDate = Carbon::createFromFormat('H:i', $working_hours['end'] ?? '18:00');

            $check = Carbon::now()->between($startDate, $endDate, true);
            if ($check) {
                return true;
            }
        }

        return false;
    }

    /**
     * Country relation.
     *
     * @return BelongsToMany
     */
    public function countries(): BelongsToMany
    {
        return $this
            ->belongsToMany(Country::class, 'campaign_country')
            ->withPivot('cpa', 'working_hours', 'daily_cap', 'crg');
    }
}
