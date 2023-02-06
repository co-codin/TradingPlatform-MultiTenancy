<?php

declare(strict_types=1);

namespace Modules\User\Services;

use App\Services\Storage\Traits\SyncHelper;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\DisplayOption;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Tenant;

final class UserStorage
{
    use SyncHelper;

    /**
     * @param  array  $attributes
     * @return User
     */
    public function store(array $attributes): User
    {
        $attributes['password'] = Hash::make($attributes['password']);

        $user = User::create($attributes);

        $user->roles()->sync(Arr::pluck($attributes['roles'], 'id'));

        $this->syncBelongsToManyWithPivot($user, $attributes, 'languages');
        $this->syncBelongsToManyWithPivot($user, $attributes, 'countries');
        $this->syncBelongsToManyWithPivot($user, $attributes, 'brands');

        if (Tenant::checkCurrent()) {
            $this->syncBelongsToManyWithPivot($user, $attributes, 'desks');

            if ($attributes['worker_info']) {
                if ($user->workerInfo()->exists()) {
                    $user->workerInfo()->update($attributes['worker_info']);
                } else {
                    $user->workerInfo()->create($attributes['worker_info']);
                }
            }
        }

        return $user;
    }

    /**
     * @param  User  $user
     * @param  array  $attributes
     * @return User
     *
     * @throws Exception
     */
    public function update(User $user, array $attributes): User
    {
        if (isset($attributes['change_password'], $attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        if (! $user->update($attributes)) {
            throw new Exception('Cant update user');
        }

        $this->syncBelongsToManyWithPivot($user, $attributes, 'roles');
        $this->syncBelongsToManyWithPivot($user, $attributes, 'brands');

        if (Tenant::checkCurrent()) {
            if ($attributes['worker_info']) {
                if ($user->workerInfo()->exists()) {
                    $user->workerInfo()->update($attributes['worker_info']);
                } else {
                    $user->workerInfo()->create($attributes['worker_info']);
                }
            }
        }

        return $user;
    }

    /**
     * @param  User  $user
     *
     * @throws Exception
     */
    public function destroy(User $user): void
    {
        if (! $user->delete()) {
            throw new Exception('Cant delete user');
        }
    }

    /**
     * Store user display option.
     *
     * @param  User  $user
     * @param  array  $attributes
     * @return DisplayOption
     */
    public function storeDisplayOption(User $user, array $attributes): DisplayOption
    {
        return $user->displayOptions()->create($attributes);
    }

    /**
     * Update user display option.
     *
     * @param  User  $user
     * @param  int  $displayOptionId
     * @param  array  $attributes
     * @return DisplayOption
     *
     * @throws Exception
     */
    public function updateDisplayOption(User $user, int $displayOptionId, array $attributes): DisplayOption
    {
        $displayOption = $user->displayOptions()
            ->find($displayOptionId);

        if (! $displayOption?->update($attributes)) {
            throw new Exception('Cant update user display option');
        }

        return $displayOption;
    }

    /**
     * Destroy user display option.
     *
     * @param  User  $user
     * @param  int  $displayOptionId
     * @return void
     *
     * @throws Exception
     */
    public function destroyDisplayOption(User $user, int $displayOptionId): void
    {
        $displayOption = $user->displayOptions()
            ->find($displayOptionId);

        if (! $displayOption?->delete()) {
            throw new Exception('Cant destroy user display option');
        }
    }
}
