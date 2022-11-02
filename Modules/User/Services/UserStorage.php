<?php


namespace Modules\User\Services;


use Exception;
use Hash;
use Modules\User\Models\User;

class UserStorage
{
    /**
     * @param array $attributes
     * @return User
     */
    public function store(array $attributes): User
    {
        $attributes['password'] = Hash::make($attributes['password']);

        $user = User::create($attributes);

        $user->assignRole($attributes['role_id']);

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
        if ($attributes['change_password']) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        if (!$user->update($attributes)) {
            throw new Exception('Cant update user');
        }

        $user->syncRoles($attributes['role_id']);

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
