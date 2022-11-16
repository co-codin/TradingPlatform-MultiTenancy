<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin\Desk;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\User\Http\Requests\Desk\UserDeskUpdateRequest;
use Modules\User\Repositories\UserRepository;
use OpenApi\Annotations as OA;

final class UserDeskController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
    ) {
    }

    /**
     * @OA\Put(
     *     path="/admin/users/{id}/desk",
     *     tags={"User"},
     *     security={ {"sanctum": {} }},
     *     summary="Update user desks",
     *     @OA\Parameter(
     *         description="User id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"desks"},
     *                 @OA\Property(property="desks", type="array", @OA\Items(required={"id"}, @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     description="Desk id",
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
    public function update(UserDeskUpdateRequest $request, int $id): void
    {
        $user = $this->userRepository->find($id);

        $this->authorize('update', $user);

        $ids = $request->collect('desks')->pluck('id')->filter()->unique();

        $user->desks()->sync($ids);
    }
}
