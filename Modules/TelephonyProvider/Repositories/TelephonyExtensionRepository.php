<?php

declare(strict_types=1);

namespace Modules\Sale\Repositories;

use App\Repositories\BaseRepository;
use Modules\Sale\Models\SaleStatus;
use Modules\Sale\Repositories\Criteria\TelephonyExtensionRequestCriteria;
use Modules\Sale\Repositories\Criteria\TelephonyProviderRequestCriteria;
use Modules\TelephonyProvider\Models\TelephonyExtension;
use Modules\TelephonyProvider\Models\TelephonyProvider;
use Prettus\Repository\Exceptions\RepositoryException;

final class TelephonyExtensionRepository extends BaseRepository
{
    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return TelephonyExtension::class;
    }

    /**
     * @inheritDoc
     * @throws RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(TelephonyExtensionRequestCriteria::class);
    }
}
