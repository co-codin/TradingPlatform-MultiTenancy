<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin\Department;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\User\Http\Requests\Department\UserDepartmentUpdateRequest;
use Modules\User\Repositories\UserRepository;

final class UserDepartmentController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
    ) {
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UserDepartmentUpdateRequest $request, int $id): void
    {
        $user = $this->userRepository->find($id);

        $this->authorize('update', $user);

        $ids = $request->collect('departments')->pluck('id')->filter()->unique();

        $user->departments()->sync($ids);
    }
}
