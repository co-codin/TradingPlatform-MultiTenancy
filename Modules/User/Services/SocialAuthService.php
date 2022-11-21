<?php

declare(strict_types=1);

namespace Modules\User\Services;

use Laravel\Socialite\Facades\Socialite;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;

final class SocialAuthService
{
    private string $provider;

    /**
     * @param  UserRepository  $repository
     */
    public function __construct(
        private readonly UserRepository $repository
    ) {
    }

    /**
     * @return User|null
     */
    public function findUser(): ?User
    {
        $providerUser = Socialite::driver($this->provider)->user();

        return $this->repository->findByField(['email' => $providerUser->getEmail()])->first();
    }

    /**
     * @param  string  $provider
     */
    public function setProvider(string $provider): void
    {
        $this->provider = $provider;
    }
}
