<?php
declare(strict_types=1);

namespace Modules\Worker\Services;

use Exception;
use Modules\Worker\Models\Worker;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class WorkerStorage
{
    /**
     * @param array $attributes
     * @return Worker
     */
    public function store(array $attributes): Worker
    {
        $attributes['password'] = Hash::make($attributes['password']);

        $user = Worker::create($attributes);

        $user->assignRole($attributes['role_id']);

        return $user;
    }

    /**
     * @param Worker $user
     * @param array $attributes
     * @return Worker
     * @throws Exception
     */
    public function update(Worker $user, array $attributes): Worker
    {
        if ($attributes['change_password']) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        if (!$user->update($attributes)) {
            throw new RuntimeException('Cant update user');
        }

        $user->syncRoles($attributes['role_id']);

        return $user;
    }

    /**
     * @param Worker $user
     * @throws Exception
     */
    public function destroy(Worker $user): void
    {
        if (!$user->delete()) {
            throw new RuntimeException('Cant delete user');
        }
    }
}
