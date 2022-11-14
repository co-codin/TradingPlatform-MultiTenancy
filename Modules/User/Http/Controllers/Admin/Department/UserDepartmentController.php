<?php

namespace Modules\User\Http\Controllers\Admin\Department;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Department\Models\Department;
use Modules\User\Http\Requests\Department\UserDepartmentUpdateRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Repositories\UserRepository;
use Modules\User\Http\Requests\Brand\UserBrandUpdateRequest;

class UserDepartmentController extends Controller
{
    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        protected UserRepository $userRepository
    )
    {
        //
    }

    /**
     * @OA\Patch(
     *      path="/admin/users/{userId}/department",
     *      operationId="users.departments.update",
     *      tags={"User"},
     *      summary="Update user departments",
     *      description="Returns user data.",
     *      @OA\Parameter(
     *          description="User id",
     *          in="path",
     *          name="userId",
     *          required=true,
     *          example=1
     *      ),
     *      @OA\Parameter(
     *          description="Department ids",
     *          in="path",
     *          name="departments",
     *          required=true,
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(type="integer"),
     *              example={1,2}
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     *
     * Update user department.
     *
     * @param UserDepartmentUpdateRequest $request
     * @param int $user
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function update(UserDepartmentUpdateRequest $request, int $user): JsonResource
    {
        $user = $this->userRepository->find($user);

        $this->authorize('update', $user);

        $user->departments()->sync($request->get('departments'));

        return new UserResource($user->load('departments'));
    }
}
