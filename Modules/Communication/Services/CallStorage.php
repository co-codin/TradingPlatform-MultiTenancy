<?php

declare(strict_types=1);

namespace Modules\Communication\Services;

use LogicException;
use Modules\Communication\Dto\CallDto;
use Modules\Communication\Models\Call;

final class CallStorage
{
    /**
     * Store call.
     *
     * @param  CallDto  $dto
     * @return Call
     */
    public function store(CallDto $dto): Call
    {
        $call = Call::create($dto->toArray());

        if (!$call) {
            throw new LogicException(__('Can not create call'));
        }

        return $call;
    }

    /**
     * Update call.
     *
     * @param  Call  $call
     * @param  CallDto  $dto
     * @return Call
     *
     * @throws LogicException
     */
    public function update(Call $call, CallDto $dto): Call
    {
        if (!$call->update($dto->toArray())) {
            throw new LogicException(__('Can not update call'));
        }

        return $call;
    }

    /**
     * Delete call.
     *
     * @param  Call  $call
     * @return bool
     */
    public function delete(Call $call): bool
    {
        if (!$call->delete()) {
            throw new LogicException(__('Can not delete call'));
        }

        return true;
    }
}
