<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin\Brand;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\User\Http\Requests\Brand\UserBrandUpdateRequest;
use Modules\User\Repositories\UserRepository;

final class UserBrandController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
    ) {
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UserBrandUpdateRequest $request, int $id): void
    {
        $user = $this->userRepository->find($id);

        $this->authorize('update', $user);

        $ids = $request->get('brands')->pluck('id')->filter()->unique();

        $user->brands()->sync($ids);
    }
}
