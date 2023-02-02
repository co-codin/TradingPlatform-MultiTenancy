<?php

declare(strict_types=1);

namespace Modules\User\Services;

use App\Services\Storage\Traits\SyncHelper;
use Exception;
use Illuminate\Support\Arr;
use Modules\User\Dto\WorkerInfoDto;
use Modules\User\Models\WorkerInfo;

final class WorkerInfoStorage
{
    use SyncHelper;

    /**
     * @param WorkerInfoDto $dto
     * @return WorkerInfo
     * @throws Exception
     */
    public function store(WorkerInfoDto $dto): WorkerInfo
    {
        if (! $workerInfo = WorkerInfo::query()->create($dto->toArray())) {
            throw new Exception('Cant create worker info');
        }

        return $workerInfo;
    }

    /**
     * @param WorkerInfo $workerInfo
     * @param WorkerInfoDto $dto
     * @return WorkerInfo
     *
     * @throws Exception
     */
    public function update(WorkerInfo $workerInfo, WorkerInfoDto $dto): WorkerInfo
    {
        if (! $workerInfo->update($dto->toArray())) {
            throw new Exception('Cant update worker info');
        }

        return $workerInfo;
    }

    /**
     * @param WorkerInfoDto $dto
     * @return WorkerInfo
     * @throws Exception
     */
    public function updateOrCreate(WorkerInfoDto $dto): WorkerInfo
    {
        $data = $dto->toArray();

        $workerInfo = WorkerInfo::query()->updateOrCreate(
            Arr::only($data, ['id']),
            Arr::except($data, ['id']),
        );

        if (! $workerInfo) {
            throw new Exception('Cant update or create worker info');
        }

        return $workerInfo;
    }
}
