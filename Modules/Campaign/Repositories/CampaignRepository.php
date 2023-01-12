<?php

declare(strict_types=1);

namespace Modules\Campaign\Repositories;

use App\Repositories\BaseRepository;
use Modules\Campaign\Models\Campaign;
use Modules\Campaign\Repositories\Criteria\CampaignRequestCriteria;

final class CampaignRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    final public function model(): string
    {
        return Campaign::class;
    }

    /**
     * {@inheritDoc}
     */
    final public function boot()
    {
        $this->pushCriteria(CampaignRequestCriteria::class);
    }
}
