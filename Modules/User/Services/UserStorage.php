<?php

declare(strict_types=1);

namespace Modules\User\Services;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;

final class UserStorage
{
    /**
     * @param array $attributes
     * @return User
     */
    public function store(array $attributes): User
    {
        $attributes['password'] = Hash::make($attributes['password']);

        $user = User::create($attributes);

        $user->assignRole($attributes['roles']);

        return $user;
    }

    /**
     * @param User $user
     * @param array $attributes
     * @return User
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

        if (isset($attributes['roles'])) {
            $user->syncRoles($attributes['roles']);
        }

        return $user;
    }

    /**
     * @param User $user
     * @throws Exception
     */
    public function destroy(User $user): void
    {
        if (!$user->delete()) {
            throw new Exception('Cant delete user');
        }
    }
}
