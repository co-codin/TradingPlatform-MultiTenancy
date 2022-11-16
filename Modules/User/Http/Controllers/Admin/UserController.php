<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\User\Http\Requests\UserBanRequest;
use Illuminate\Support\Arr;
use Modules\User\Http\Requests\UserCreateRequest;
use Modules\User\Http\Requests\UserUpdateBatchRequest;
use Modules\User\Http\Requests\UserUpdateRequest;
use Modules\User\Http\Resources\WorkerResource;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\UserStorage;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

final class UserController extends Controller
{
    public function __construct(
        protected UserStorage $userStorage,
        protected UserRepository $userRepository
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/workers",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Get workers",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/WorkerCollection")
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     )
     * )
     *
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);
        $users = $this->userRepository->jsonPaginate();

        return WorkerResource::collection($users);
    }

    /**
     * @OA\Get(
     *     path="/admin/workers/{id}",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Get worker data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User id",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/WorkerResource")
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * )
     *
     * @param  int  $id
     * @return WorkerResource
     * @throws AuthorizationException
     */
    public function show(int $id): WorkerResource
    {
        $user = $this->userRepository->find($id);
        $this->authorize('view', $user);

        return new WorkerResource($user);
    }

    /**
     * @OA\Post(
     *     path="/admin/workers",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new worker",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "username",
     *                     "first_name",
     *                     "last_name",
     *                     "email",
     *                     "password",
     *                     "password_confirmation",
     *                     "role_id",
     *                 },
     *                 @OA\Property(property="username", type="string"),
     *                 @OA\Property(property="first_name", type="string"),
     *                 @OA\Property(property="last_name", type="string"),
     *                 @OA\Property(property="email", type="string", format="email"),
     *                 @OA\Property(property="password", type="string", format="password"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password"),
     *                 @OA\Property(property="is_active", type="boolean"),
     *                 @OA\Property(property="target", type="integer"),
     *                 @OA\Property(property="parent_id", type="integer"),
     *                 @OA\Property(property="roles", type="array", @OA\Items(type="integer")),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/WorkerResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     * )
     * @throws AuthorizationException
     */
    public function store(UserCreateRequest $request): WorkerResource
    {
        $this->authorize('create', User::class);

        $user = $this->userStorage->store($request->validated());

        return new WorkerResource($user);
    }

    /**
     * @OA\Put(
     *     path="/admin/workers/{id}",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a worker",
     *     @OA\Parameter(
     *         description="Worker id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "username",
     *                     "first_name",
     *                     "last_name",
     *                     "email",
     *                     "password",
     *                     "password_confirmation",
     *                     "role_id",
     *                 },
     *                 @OA\Property(property="username", type="string"),
     *                 @OA\Property(property="first_name", type="string"),
     *                 @OA\Property(property="last_name", type="string"),
     *                 @OA\Property(property="email", type="string", format="email"),
     *                 @OA\Property(property="password", type="string", format="password"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password"),
     *                 @OA\Property(property="is_active", type="boolean"),
     *                 @OA\Property(property="target", type="integer"),
     *                 @OA\Property(property="parent_id", type="integer"),
     *                 @OA\Property(property="role_id", type="array", @OA\Items(type="integer")),
     *                 @OA\Property(property="change_password", type="boolean",
     *                     description="Must be set to true if the password is changed."),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/WorkerResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * ),
     * @OA\Patch(
     *     path="/admin/workers/{id}",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a worker",
     *     @OA\Parameter(
     *         description="Worker id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="username", type="string"),
     *                 @OA\Property(property="first_name", type="string"),
     *                 @OA\Property(property="last_name", type="string"),
     *                 @OA\Property(property="email", type="string", format="email"),
     *                 @OA\Property(property="password", type="string", format="password"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password"),
     *                 @OA\Property(property="is_active", type="boolean"),
     *                 @OA\Property(property="target", type="integer"),
     *                 @OA\Property(property="parent_id", type="integer"),
     *                 @OA\Property(property="roles", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer"),
     *                     ),
     *                 ),
     *                 @OA\Property(property="change_password", type="boolean",
     *                     description="Must be set to true if the password is changed."),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/WorkerResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * )
     * @throws AuthorizationException
     */
    public function update(int $id, UserUpdateRequest $request): WorkerResource
    {
        $user = $this->userRepository->find($id);

        $this->authorize('update', $user);

        $user = $this->userStorage->update($user, $request->validated());

        return new WorkerResource($user->load('roles'));
    }

    /**
     * @OA\Delete(
     *     path="/admin/workers/{id}",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a worker",
     *     @OA\Parameter(
     *         description="Worker id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * )
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(int $id): Response
    {
        $user = $this->userRepository->find($id);

        $this->authorize('delete', $user);

        $this->userStorage->destroy($user);

        return response()->noContent();
    }

    /**
     * @OA\Patch (
     *     path="/admin/workers/ban",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Ban a worker",
     *     @OA\Parameter(
     *         description="Users data",
     *         in="path",
     *         name="users",
     *         required=true,
     *         @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="id", type="integer"),
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * )
     *
     * Ban user.
     *
     * @param UserBanRequest $request
     * @return JsonResource
     *
     * @throws Exception
     */
    public function ban(UserBanRequest $request): JsonResource
    {
        $users = collect();

        foreach ($request->get('users', []) as $item) {
            $user = $this->userRepository->find($item['id']);

            if ($request->user()->can('ban', $user)) {
                $users->push(
                    $this->userStorage->update($user, ['banned_at' => Carbon::now()->toDateTimeString()])
                );
            }
        }

        abort_if($users->isEmpty(), ResponseAlias::HTTP_UNAUTHORIZED);

        return WorkerResource::collection($users);
    }

    /**
     * @OA\Patch (
     *     path="/admin/workers/unban",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Unban a worker",
     *     @OA\Parameter(
     *         description="Users data",
     *         in="path",
     *         name="users",
     *         required=true,
     *         @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="id", type="integer"),
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * )
     *
     * Ban user.
     *
     * @param UserBanRequest $request
     * @return JsonResource
     *
     * @throws Exception
     */
    public function unban(UserBanRequest $request): JsonResource
    {
        $users = collect();

        foreach ($request->get('users', []) as $item) {
            $user = $this->userRepository->find($item['id']);

            if ($request->user()->can('ban', $user)) {
                $users->push(
                    $this->userStorage->update($user, ['banned_at' => null])
                );
            }
        }

        abort_if($users->isEmpty(), ResponseAlias::HTTP_UNAUTHORIZED);

        return WorkerResource::collection($users);
    }

    /**
     * @OA\Patch (
     *     path="/admin/workers/batch",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Unban a worker",
     *     @OA\Parameter(
     *         description="Users data",
     *         in="path",
     *         name="users",
     *         required=true,
     *         @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="username", type="string"),
     *                  @OA\Property(property="first_name", type="string"),
     *                  @OA\Property(property="last_name", type="string"),
     *                  @OA\Property(property="email", type="string", format="email"),
     *                  @OA\Property(property="password", type="string", format="password"),
     *                  @OA\Property(property="password_confirmation", type="string", format="password"),
     *                  @OA\Property(property="is_active", type="boolean"),
     *                  @OA\Property(property="target", type="integer"),
     *                  @OA\Property(property="parent_id", type="integer"),
     *                  @OA\Property(property="roles", type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="id", type="integer"),
     *                      ),
     *                  ),
     *                  @OA\Property(property="change_password", type="boolean",
     *                      description="Must be set to true if the password is changed."),
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/WorkerCollection")
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * )
     *
     * Update batch users.
     *
     * @param UserUpdateBatchRequest $request
     * @return JsonResource
     *
     * @throws Exception
     */
    public function updateBatch(UserUpdateBatchRequest $request): JsonResource
    {
        $users = collect();

        foreach ($request->get('users', []) as $item) {
            $user = $this->userRepository->find($item['id']);

            if ($request->user()->can('update', $user)) {
                $users->push(
                    $this->userStorage->update($user, Arr::except($item, ['id']))
                );
            }
        }

        abort_if($users->isEmpty(), ResponseAlias::HTTP_UNAUTHORIZED);

        return WorkerResource::collection($users);
    }
}
