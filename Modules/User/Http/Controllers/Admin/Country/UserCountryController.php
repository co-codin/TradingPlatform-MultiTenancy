<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin\Country;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\User\Http\Requests\Country\UserCountryUpdateRequest;
use Modules\User\Repositories\UserRepository;

final class UserCountryController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
    ) {
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UserCountryUpdateRequest $request, int $id): void
    {
        $user = $this->userRepository->find($id);

        $this->authorize('update', $user);

        $ids = $request->collect('countries')->pluck('id')->filter()->unique();

        $user->countries()->sync($ids);
    }
}
