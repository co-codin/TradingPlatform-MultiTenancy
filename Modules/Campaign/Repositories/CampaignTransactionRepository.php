<?php

declare(strict_types=1);

namespace Modules\Campaign\Repositories;

use App\Repositories\BaseRepository;
use Modules\Campaign\Models\CampaignTransaction;
use Modules\Campaign\Repositories\Criteria\CampaignTransactionRequestCriteria;

final class CampaignTransactionRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    final public function model(): string
    {
        return CampaignTransaction::class;
    }

    /**
     * {@inheritDoc}
     */
    final public function boot()
    {
        $this->pushCriteria(CampaignTransactionRequestCriteria::class);
    }
}
