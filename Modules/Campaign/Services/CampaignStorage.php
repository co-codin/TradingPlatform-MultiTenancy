<?php

declare(strict_types=1);

namespace Modules\Campaign\Services;

use App\Services\Storage\Traits\HasBelongsToMany;
use Exception;
use Modules\Campaign\Dto\CampaignDto;
use Modules\Campaign\Models\Campaign;

final class CampaignStorage
{
    use HasBelongsToMany;

    /**
     * Store.
     *
     * @param  CampaignDto  $campaignDto
     * @return Campaign
     *
     * @throws Exception
     */
    public function store(CampaignDto $campaignDto): Campaign
    {
        $data = $campaignDto->toArray();

        if (! $campaign = Campaign::query()->create($data)) {
            throw new Exception(__('Can not store campaign'));
        }

        $this->syncBelongsToManyWithPivot($campaign, $data, 'countries');

        return $campaign;
    }

    /**
     * Update.
     *
     * @param  Campaign  $campaign
     * @param  CampaignDto  $campaignDto
     * @return Campaign
     *
     * @throws Exception
     */
    public function update(Campaign $campaign, CampaignDto $campaignDto): Campaign
    {
        $data = $campaignDto->toArray();

        if (! $campaign->update($campaignDto->toArray())) {
            throw new Exception('Can not update campaign');
        }

        $this->syncBelongsToManyWithPivot($campaign, $data, 'countries');

        return $campaign;
    }

    /**
     * Change status.
     *
     * @param  Campaign  $campaign
     * @return Campaign
     *
     * @throws Exception
     */
    public function changeStatus(Campaign $campaign): Campaign
    {
        if (! $campaign->update(['is_active' => ! $campaign->is_active])) {
            throw new Exception('Can not change status of campaign');
        }

        return $campaign;
    }
}
