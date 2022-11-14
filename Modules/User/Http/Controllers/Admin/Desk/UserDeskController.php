<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin\Desk;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\User\Http\Requests\Desk\UserDeskUpdateRequest;
use Modules\User\Repositories\UserRepository;

final class UserDeskController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
    ) {
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UserDeskUpdateRequest $request, int $id): void
    {
        $user = $this->userRepository->find($id);

        $this->authorize('update', $user);

        $ids = $request->collect('desks')->pluck('id')->filter()->unique();

        $user->desks()->sync($ids);
    }
}
