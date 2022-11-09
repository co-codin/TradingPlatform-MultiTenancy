<?php

namespace Modules\User\Http\Controllers\Admin\Brand;

use App\Http\Controllers\Controller;
use Modules\User\Repositories\UserRepository;
use Modules\User\Http\Requests\Brand\UserBrandUpdateRequest;

class UserBrandController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function update(UserBrandUpdateRequest $request, int $user)
    {
        $user = $this->userRepository->find($user);

        $this->authorize('update', $user);

        $ids = $request->get('brands')->pluck('id')->filter()->unique();

        $user->brands()->sync($ids);
    }
}