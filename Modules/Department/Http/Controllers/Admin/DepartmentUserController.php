<?php

namespace Modules\Department\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Department\Repositories\DepartmentRepository;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Repositories\UserRepository;

class DepartmentUserController
{
    /**
     * @param DepartmentRepository $departmentRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        protected DepartmentRepository $departmentRepository,
        protected UserRepository $userRepository,
    )
    {
    }

    /**
     * @return JsonResource
     */
    public function allByDepartments(Request $request): JsonResource
    {
        $authUser = $request->user();

        return UserResource::collection();
    }
}
