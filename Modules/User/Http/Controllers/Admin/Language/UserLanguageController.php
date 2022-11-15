<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin\Language;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\User\Http\Requests\Language\UserLanguageUpdateRequest;
use Modules\User\Repositories\UserRepository;
use OpenApi\Annotations as OA;

final class UserLanguageController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
    ) {
    }

    /**
     * @OA\Put(
     *     path="/admin/users/{id}/language",
     *     tags={"User"},
     *     security={ {"sanctum": {} }},
     *     summary="Update user languages",
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
     *                 required={"languages"},
     *                 @OA\Property(property="languages", type="array", @OA\Items(required={"id"}, @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     description="Language id",
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
    public function update(UserLanguageUpdateRequest $request, int $id): void
    {
        $user = $this->userRepository->find($id);

        $this->authorize('update', $user);

        $ids = $request->collect('languages')->pluck('id')->filter()->unique();

        $user->languages()->sync($ids);
    }
}
