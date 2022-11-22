<?php

declare(strict_types=1);

namespace Modules\User\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\Traits\HasAuthUser;

final class UserBanService
{
    use HasAuthUser;

    /**
     * @param UserStorage $userStorage
     * @param UserRepository $userRepository
     */
    final public function __construct(
        protected UserStorage $userStorage,
        protected UserRepository $userRepository
    )
    {}

    /**
     * Ban user.
     *
     * @param User $user
     * @return User|null
     * @throws Exception
     */
    final public function banUser(User $user): ?User
    {
        if ($this->authUser?->can('ban', $user)) {
            return $this->userStorage->update($user, ['banned_at' => Carbon::now()->toDateTimeString()]);
        }

        return null;
    }

    /**
     * Ban users.
     *
     * @param array $usersData
     * @return Collection
     * @throws Exception
     */
    final public function banUsers(array $usersData): Collection
    {
        $bannedUsers = collect();

        foreach ($usersData as $userData) {
            $user = $this->userRepository->find($userData['id']);

            $bannedUsers->push($this->banUser($user));
        }

        return $bannedUsers;
    }

    /**
     * Unban user.
     *
     * @param User $user
     * @return User|null
     * @throws Exception
     */
    final public function unbanUser(User $user): ?User
    {
        if ($this->authUser?->can('unban', $user)) {
            return $this->userStorage->update($user, ['banned_at' => null]);
        }

        return null;
    }

    /**
     * Unban users.
     *
     * @param array $usersData
     * @return Collection
     * @throws Exception
     */
    final public function unbanUsers(array $usersData): Collection
    {
        $unbannedUsers = collect();

        foreach ($usersData as $userData) {
            $user = $this->userRepository->find($userData['id']);

            $unbannedUsers->push($this->unbanUser($user));
        }

        return $unbannedUsers;
    }
}
