<?php

declare(strict_types=1);

namespace Modules\Campaign\Services;

use Exception;
use Modules\Campaign\Dto\CampaignDto;
use Modules\Campaign\Models\Campaign;

final class CampaignStorage
{
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
        if (! $campaign = Campaign::query()->create($campaignDto->toArray())) {
            throw new Exception(__('Can not store campaign'));
        }

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
        if (! $campaign->update($campaignDto->toArray())) {
            throw new Exception('Can not update campaign');
        }

        if ($campaignDto->countries) {
            $campaign->countries()->sync($campaignDto->countries);
        }

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
