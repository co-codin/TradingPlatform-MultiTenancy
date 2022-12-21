<?php

declare(strict_types=1);

namespace Modules\User\Services;

use Exception;
use Modules\User\Models\Preset;
use Modules\User\Models\User;

final class UserPresetStorage
{
    /**
     * Store user preset.
     *
     * @param User $user
     * @param array $attributes
     * @return Preset
     */
    public function store(User $user, array $attributes): Preset
    {
        return $user->presets()->create($attributes);
    }

    /**
     * Update user preset.
     *
     * @param User $user
     * @param Preset $preset
     * @param array $attributes
     * @return Preset
     * @throws Exception
     */
    public function update(User $user, Preset $preset, array $attributes): Preset
    {
        if (! $preset?->update($attributes)) {
            throw new Exception(__('Cant update user preset'));
        }

        return $preset;
    }

    /**
     * Destroy user display option.
     *
     * @param User $user
     * @param Preset $preset
     * @return void
     * @throws Exception
     */
    public function destroy(User $user, Preset $preset): void
    {
        if (! $preset?->delete()) {
            throw new Exception(__('Cant destroy user preset'));
        }
    }
}
