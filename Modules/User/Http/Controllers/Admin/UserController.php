<?php


namespace Modules\User\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Modules\User\Http\Requests\UserCreateRequest;
use Modules\User\Http\Requests\UserUpdateRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\UserStorage;

class UserController extends Controller
{
    public function __construct(
        protected UserStorage $userStorage,
        protected UserRepository $userRepository
    ) {}

    public function index()
    {
        $users = $this->userRepository->jsonPaginate();

        return UserResource::collection($users);
    }

    public function show(int $user)
    {
        $user = $this->userRepository->find($user);

        return new UserResource($user);
    }

    public function store(UserCreateRequest $request)
    {
        $this->authorize('create', User::class);

        $user = $this->userStorage->store($request->validated());

        return new UserResource($user);
    }

    public function update(int $user, UserUpdateRequest $request)
    {
        $user = $this->userRepository->find($user);

        $this->authorize('update', $user);

        $user = $this->userStorage->update($user, $request->validated());

        return new UserResource($user);
    }

    public function destroy(int $user)
    {
        $user = $this->userRepository->find($user);

        $this->authorize('delete', $user);

        $this->userStorage->destroy($user);

        return response()->noContent();
    }
}
