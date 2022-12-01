<?php

namespace Modules\Department\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Department\Repositories\DepartmentRepository;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;

class DepartmentUserController extends Controller
{
    /**
     * @param  DepartmentRepository  $departmentRepository
     * @param  UserRepository  $userRepository
     */
    public function __construct(
        protected DepartmentRepository $departmentRepository,
        protected UserRepository $userRepository,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/departments/workers",
     *     tags={"Department"},
     *     security={ {"sanctum": {} }},
     *     summary="Get departments workers",
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
     * View all users by departments.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function allByDepartments(Request $request): JsonResource
    {
        $this->authorize('viewAnyByDepartments', User::class);

        return UserResource::collection(
            $this->userRepository
                ->whereHas('departments', function ($query) use ($request) {
                    $query->whereIn(
                        'departments.id',
                        $this->departmentRepository
                            ->whereHas('users', fn ($q) => $q->where('users.id', $request->user()->id))
                            ->get()
                            ->pluck('id')
                            ->toArray(),
                    );
                })
                ->get()
        );
    }
}
