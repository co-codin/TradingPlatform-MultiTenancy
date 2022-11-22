<?php

declare(strict_types=1);

namespace Modules\User\Services\Traits;

use Modules\User\Models\User;

trait HasAuthUser
{
    /**
     * @var User|null
     */
    protected ?User $authUser = null;

    /**
     * Set auth user for banning users.
     *
     * @param User $authUser
     * @return $this
     */
    final public function setAuthUser(User $authUser): static
    {
        $this->authUser = $authUser;

        return $this;
    }
}
