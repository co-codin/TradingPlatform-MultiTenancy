<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\User\Http\Requests\UserBanRequest;
use Modules\User\Http\Requests\UserCreateRequest;
use Modules\User\Http\Requests\UserUpdateBatchRequest;
use Modules\User\Http\Requests\UserUpdateRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\UserBanService;
use Modules\User\Services\UserBatchService;
use Modules\User\Services\UserStorage;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

final class UserController extends Controller
{
    public function __construct(
        protected UserStorage $userStorage,
        protected UserRepository $userRepository,
        protected UserBanService $userBanService,
        protected UserBatchService $userBatchService,
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
     *
     * @throws AuthorizationException
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);

        $users = $this->userRepository->jsonPaginate();

        return UserResource::collection($users);
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
     *          description="Worker ID",
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
     * @return UserResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): UserResource
    {
        $user = $this->userRepository->find($id);

        $this->authorize('view', $user);

        return new UserResource($user);
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
     *                     "password",
     *                     "password_confirmation",
     *                     "roles",
     *                 },
     *                 @OA\Property(property="username", description="Worker username"),
     *                 @OA\Property(property="first_name", type="string", description="First name"),
     *                 @OA\Property(property="last_name", type="string", description="Last name"),
     *                 @OA\Property(property="email", type="string", format="email", description="Email"),
     *                 @OA\Property(property="password", type="string", format="password", description="Password"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password", description="Password confirmation"),
     *                 @OA\Property(property="is_active", type="boolean", description="Worker activity flag"),
     *                 @OA\Property(property="target", type="integer", description="Target amount for the worker"),
     *                 @OA\Property(property="parent_id", type="integer", description="Parent worker ID"),
     *                 @OA\Property(property="roles", type="array", description="Array of roles ID",
     *                     @OA\Items(@OA\Property(property="id", type="integer")),
     *                 ),
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
     *
     * @throws AuthorizationException
     */
    public function store(UserCreateRequest $request): UserResource
    {
        $this->authorize('create', User::class);

        $user = $this->userStorage->store($request->validated());

        return new UserResource($user);
    }

    /**
     * @OA\Put(
     *     path="/admin/workers/{id}",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a worker",
     *     @OA\Parameter(
     *         description="Worker ID",
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
     *                     "password",
     *                     "password_confirmation",
     *                     "roles",
     *                 },
     *                 @OA\Property(property="username", type="string", description="Worker username"),
     *                 @OA\Property(property="first_name", type="string", description="First name"),
     *                 @OA\Property(property="last_name", type="string", description="Last name"),
     *                 @OA\Property(property="email", type="string", format="email", description="Email"),
     *                 @OA\Property(property="password", type="string", format="password", description="Password"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password", description="Password confirmation"),
     *                 @OA\Property(property="is_active", type="boolean", description="Worker activity flag"),
     *                 @OA\Property(property="target", type="integer", description="Target amount for the worker"),
     *                 @OA\Property(property="parent_id", type="integer", description="Parent worker ID"),
     *                 @OA\Property(property="roles", type="array", description="Array of roles ID",
     *                     @OA\Items(@OA\Property(property="id", type="integer")),
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
     * ),
     * @OA\Patch(
     *     path="/admin/workers/{id}",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a worker",
     *     @OA\Parameter(
     *         description="Worker ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="username", type="string", description="Worker username"),
     *                 @OA\Property(property="first_name", type="string", description="First name"),
     *                 @OA\Property(property="last_name", type="string", description="Last name"),
     *                 @OA\Property(property="email", type="string", format="email", description="Email"),
     *                 @OA\Property(property="password", type="string", format="password", description="Password"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password", description="Password confirmation"),
     *                 @OA\Property(property="is_active", type="boolean", description="Worker activity flag"),
     *                 @OA\Property(property="target", type="integer", description="Target amount for the worker"),
     *                 @OA\Property(property="parent_id", type="integer", description="Parent worker ID"),
     *                 @OA\Property(property="roles", type="array", description="Array of roles ID",
     *                     @OA\Items(@OA\Property(property="id", type="integer")),
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
     *
     * @throws AuthorizationException
     * @throws Exception
     */
    public function update(UserUpdateRequest $request, int $id): UserResource
    {
        $user = $this->userRepository->find($id);

        $dd = $this->authorize('update', $user);

        $user = $this->userStorage->update($user, $request->validated());

        return new UserResource($user->load('roles'));
    }

    /**
     * @OA\Delete(
     *     path="/admin/workers/{id}",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a worker",
     *     @OA\Parameter(
     *         description="Worker ID",
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
     *
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
     *     summary="Ban workers",
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                required={"users"},
     *                @OA\Property(property="users", type="array",
     *                    @OA\Items(required={"id"},
     *                        @OA\Property(property="id", type="integer", description="Worker ID")
     *                    ),
     *                )
     *            ),
     *        ),
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
     * @param  UserBanRequest  $request
     * @return JsonResource
     *
     * @throws Exception
     */
    public function ban(UserBanRequest $request): JsonResource
    {
        $users = $this->userBanService
            ->setAuthUser($request->user())
            ->banUsers($request->validated('users', []));

        abort_if($users->isEmpty(), ResponseAlias::HTTP_UNAUTHORIZED);

        return UserResource::collection($users);
    }

    /**
     * @OA\Patch (
     *     path="/admin/workers/unban",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Unban workers",
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                required={"users"},
     *                @OA\Property(property="users", type="array",
     *                    @OA\Items(required={"id"},
     *                        @OA\Property(property="id", type="integer", description="Worker ID")
     *                    ),
     *                )
     *            ),
     *        ),
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
     * @param  UserBanRequest  $request
     * @return JsonResource
     *
     * @throws Exception
     */
    public function unban(UserBanRequest $request): JsonResource
    {
        $users = $this->userBanService
            ->setAuthUser($request->user())
            ->unbanUsers($request->validated('users', []));

        abort_if($users->isEmpty(), ResponseAlias::HTTP_UNAUTHORIZED);

        return UserResource::collection($users);
    }

    /**
     * @OA\Patch (
     *     path="/admin/workers/batch",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Batch worker update",
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                required={"users"},
     *                @OA\Property(property="users", type="array",
     *                    @OA\Items(required={"id"},
     *                        @OA\Property(property="id", type="integer", description="Worker ID"),
     *                        @OA\Property(property="username", type="string", description="Worker username"),
     *                        @OA\Property(property="first_name", type="string", description="First name"),
     *                        @OA\Property(property="last_name", type="string", description="Last name"),
     *                        @OA\Property(property="email", type="string", format="email", description="Email"),
     *                        @OA\Property(property="password", type="string", format="password", description="Password"),
     *                        @OA\Property(property="password_confirmation", type="string", format="password", description="Password confirmation"),
     *                        @OA\Property(property="is_active", type="boolean", description="Worker activity flag"),
     *                        @OA\Property(property="target", type="integer", description="Target amount for the worker"),
     *                        @OA\Property(property="parent_id", type="integer", description="Parent worker ID"),
     *                        @OA\Property(property="roles", type="array", description="Array of roles ID",
     *                            @OA\Items(@OA\Property(property="id", type="integer")),
     *                        ),
     *                        @OA\Property(property="change_password", type="boolean",
     *                            description="Must be set to true if the password is changed."),
     *                    )
     *                )
     *            ),
     *        ),
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
     * @param  UserUpdateBatchRequest  $request
     * @return JsonResource
     *
     * @throws Exception
     */
    public function updateBatch(UserUpdateBatchRequest $request): JsonResource
    {
        $users = $this->userBatchService->updateBatch($request->validated('users', []));

        abort_if($users->isEmpty(), ResponseAlias::HTTP_UNAUTHORIZED);

        return UserResource::collection($users);
    }
}
