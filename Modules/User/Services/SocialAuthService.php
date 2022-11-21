<?php
declare(strict_types=1);

namespace Modules\User\Services;

use Illuminate\Http\RedirectResponse;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse as BaseRedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthService
{
    protected UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
    }

    public function authenticate(string $provider): ?User
    {
        $providerUser = Socialite::driver($provider)->user();

        return $this->repository->findByField(['email' => $providerUser->getEmail()])->first();
    }

    public function redirect(string $provider): RedirectResponse|BaseRedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }
}
