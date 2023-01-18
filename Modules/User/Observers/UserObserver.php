<?php

declare(strict_types=1);

namespace Modules\User\Observers;

use Illuminate\Support\Str;
use Modules\Role\Enums\DefaultRole;
use Modules\User\Models\User;

final class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  User  $user
     * @return void
     */
    public function created(User $user): void
    {
        if ($user->affiliate()->exists()) {
            $user->tokens()->create([
                'name' => $user->username . $user->id,
                'token' => Str::random(),
                'ip' => request()->ip(),
            ]);

            $user->assignRole(DefaultRole::AFFILIATE);
        }
    }
}
