<?php

namespace Modules\User\Services;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Modules\User\Enums\UserPermission;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\Traits\HasAuthUser;

final class UserBatchService
{
    use HasAuthUser;

    /**
     * @param  UserRepository  $userRepository
     * @param  UserStorage  $userStorage
     */
    final public function __construct(
        protected UserRepository $userRepository,
        protected UserStorage $userStorage,
        protected WorkerInfoStorage $workerInfoStorage,
    ) {
    }

    /**
     * Update batch.
     *
     * @param  array  $usersData
     * @return Collection
     *
     * @throws Exception
     */
    final public function updateBatch(array $usersData): Collection
    {
        $updatedUsers = collect();

        foreach ($usersData as $item) {
            $user = $this->userRepository
                ->with('workerInfo')
                ->find($item['id']);

            if ($this->authUser?->can(UserPermission::EDIT_USERS, $user)) {
                $updatedUsers->push(
                    $this->userStorage->update($user, Arr::except($item, ['id']))
                );
            }
        }

        return $updatedUsers;
    }
}
