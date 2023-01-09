<?php

declare(strict_types=1);

namespace Modules\User\Observers;

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
            $this->assignAffiliateAndCreateToken($user);
        }
    }

    /**
     * Handle the User "updating" event.
     *
     * @param  User  $user
     * @return void
     */
    public function updating(User $user): void
    {
        if (! $user->getOriginal('affiliate_id') && $user->affiliate_id) {
            $this->assignAffiliateAndCreateToken($user);
        }
    }

    /**
     * Assign affiliate user and create token.
     *
     * @param User $user
     * @return void
     */
    private function assignAffiliateAndCreateToken(User $user): void
    {
        $user->createToken('api');
        $user->assignRole(DefaultRole::AFFILIATE);
    }
}
