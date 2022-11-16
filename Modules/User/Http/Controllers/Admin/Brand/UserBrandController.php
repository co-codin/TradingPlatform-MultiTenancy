<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin\Brand;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\User\Http\Requests\Brand\UserBrandUpdateRequest;
use Modules\User\Repositories\UserRepository;
use OpenApi\Annotations as OA;

final class UserBrandController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
    ) {
    }

    /**
     * @OA\Put(
     *     path="/admin/workers/{id}/brand",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Update worker brands",
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
     *                 required={"brands"},
     *                 @OA\Property(property="brands", type="array", @OA\Items(required={"id"}, @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     description="Brand id",
     *                 ))),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
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
     */
    public function update(UserBrandUpdateRequest $request, int $id): void
    {
        $user = $this->userRepository->find($id);

        $this->authorize('update', $user);

        $ids = $request->collect('brands')->pluck('id')->filter()->unique();

        $user->brands()->sync($ids);
    }
}
