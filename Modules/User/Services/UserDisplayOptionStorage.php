<?php

declare(strict_types=1);

namespace Modules\User\Services;

use Exception;
use Modules\User\Models\DisplayOption;
use Modules\User\Models\User;

final class UserDisplayOptionStorage
{
    /**
     * Store user display option.
     *
     * @param User $user
     * @param array $attributes
     * @return DisplayOption
     */
    public function store(User $user, array $attributes): DisplayOption
    {
        return $user->displayOptions()->create($attributes);
    }

    /**
     * Update user display option.
     *
     * @param User $user
     * @param int $displayOptionId
     * @param array $attributes
     * @return DisplayOption
     * @throws Exception
     */
    public function update(User $user, int $displayOptionId, array $attributes): DisplayOption
    {
        $displayOption = $user->displayOptions()
            ->find($displayOptionId);

        if (!$displayOption?->update($attributes)) {
            throw new Exception('Cant update user display option');
        }

        return $displayOption;
    }

    /**
     * Destroy user display option.
     *
     * @param User $user
     * @param int $displayOptionId
     * @return void
     * @throws Exception
     */
    public function destroy(User $user, int $displayOptionId): void
    {
        $displayOption = $user->displayOptions()
            ->find($displayOptionId);

        if (!$displayOption?->delete()) {
            throw new Exception('Cant destroy user display option');
        }
    }
}
