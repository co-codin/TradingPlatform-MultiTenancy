<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Modules\User\Http\Requests\UserCreateRequest;
use Modules\User\Http\Requests\UserUpdateRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\UserStorage;

final class UserController extends Controller
{
    public function __construct(
        protected UserStorage $userStorage,
        protected UserRepository $userRepository
    ) {
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);
        $users = $this->userRepository->jsonPaginate();

        return UserResource::collection($users);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(int $user): UserResource
    {
        $user = $this->userRepository->find($user);
        $this->authorize('view', $user);

        return new UserResource($user);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(UserCreateRequest $request): UserResource
    {
        $this->authorize('create', User::class);

        $user = $this->userStorage->store($request->validated());

        return new UserResource($user);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(int $user, UserUpdateRequest $request): UserResource
    {
        $user = $this->userRepository->find($user);

        $this->authorize('update', $user);

        $user = $this->userStorage->update($user, $request->validated());

        return new UserResource($user);
    }

    /**
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(int $user): Response
    {
        $user = $this->userRepository->find($user);

        $this->authorize('delete', $user);

        $this->userStorage->destroy($user);

        return response()->noContent();
    }
}
