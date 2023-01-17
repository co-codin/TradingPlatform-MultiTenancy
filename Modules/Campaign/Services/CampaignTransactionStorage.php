<?php

declare(strict_types=1);

namespace Modules\Campaign\Services;

use Exception;
use Modules\Campaign\Dto\CampaignTransactionDto;
use Modules\Campaign\Models\CampaignTransaction;

final class CampaignTransactionStorage
{
    /**
     * Store.
     *
     * @param  CampaignTransactionDto $campaignTransactionDto
     * @return CampaignTransaction
     *
     * @throws Exception
     */
    public function store(CampaignTransactionDto $campaignTransactionDto): CampaignTransaction
    {
        if (! $campaignTransaction = CampaignTransaction::query()->create($campaignTransactionDto->toArray())) {
            throw new Exception(__('Can not store campaign transaction'));
        }

        return $campaignTransaction;
    }

    /**
     * Update.
     *
     * @param  CampaignTransaction  $campaignTransaction
     * @param  CampaignTransactionDto  $campaignTransactionDto
     * @return CampaignTransaction
     *
     * @throws Exception
     */
    public function update(CampaignTransaction $campaignTransaction, CampaignTransactionDto $campaignTransactionDto): CampaignTransaction
    {
        if (! $campaignTransaction->update($campaignTransactionDto->toArray())) {
            throw new Exception('Can not update campaign transaction');
        }

        return $campaignTransaction;
    }
}
