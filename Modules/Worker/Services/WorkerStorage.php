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

        $worker = Worker::create($attributes);

        $worker->assignRole($attributes['role_id']);

        return $worker;
    }

    /**
     * @param Worker $worker
     * @param array $attributes
     * @return Worker
     * @throws Exception
     */
    public function update(Worker $worker, array $attributes): Worker
    {
        if ($attributes['change_password']) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        if (!$worker->update($attributes)) {
            throw new RuntimeException('Cant update user');
        }

        $worker->syncRoles($attributes['role_id']);

        return $worker;
    }

    /**
     * @param Worker $worker
     * @throws Exception
     */
    public function destroy(Worker $worker): void
    {
        if (!$worker->delete()) {
            throw new RuntimeException('Cant delete user');
        }
    }
}
