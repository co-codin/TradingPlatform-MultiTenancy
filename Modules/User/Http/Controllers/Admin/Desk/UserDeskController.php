<?php

namespace Modules\User\Http\Controllers\Admin\Desk;

use App\Http\Controllers\Controller;
use Modules\User\Http\Requests\Desk\UserDeskUpdateRequest;
use Modules\User\Repositories\UserRepository;

class UserDeskController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function update(UserDeskUpdateRequest $request, int $user)
    {
        $user = $this->userRepository->find($user);

        $this->authorize('update', $user);

        $ids = $request->get('desks')->pluck('id')->filter()->unique();

        $user->desks()->sync($ids);
    }
}
