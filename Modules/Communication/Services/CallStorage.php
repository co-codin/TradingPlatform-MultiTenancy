<?php

declare(strict_types=1);

namespace Modules\Communication\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use LogicException;
use Modules\Communication\Dto\CallDto;
use Modules\Communication\Models\Call;
use Modules\User\Models\User;

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
        $user = Auth::user();
        $call = User::find($dto->user_id)->calls()->create(array_merge($dto->toArray(), [
            'sendcallable_id' => $user->id,
            'sendcallable_type' => get_class($user),
        ]));

        if (! $call) {
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
        if (! $call->update(Arr::except($dto->toArray(), ['user_id']))) {
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
        if (! $call->delete()) {
            throw new LogicException(__('Can not delete call'));
        }

        return true;
    }
}
