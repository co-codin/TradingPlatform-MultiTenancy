<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin\Impersonate;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\User\Http\Resources\AuthUserResource;
use Modules\User\Repositories\UserRepository;
use OpenApi\Annotations as OA;

final class UserImpersonateController extends Controller
{
    public function __construct(
        protected UserRepository $repository
    ) {
    }

    /**
     * @OA\Post(
     *     path="/admin/workers/{id}/impersonate/token",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Stateless api login impersonate worker",
     *     @OA\Parameter(
     *         description="Worker id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         example="1"
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              type="object",
     *              required={
     *                  "impersonator",
     *                  "impersonator_token",
     *                  "target_worker",
     *                  "target_token",
     *                  "target_token_expired_at"
     *              },
     *              @OA\Property(property="impersonator", type="object", ref="#/components/schemas/AuthUser"),
     *              @OA\Property(property="target_worker", type="object", ref="#/components/schemas/AuthUser"),
     *              @OA\Property(property="impersonator_token", type="string"),
     *              @OA\Property(property="target_token", type="string"),
     *              @OA\Property(property="target_token_expired_at", type="string", format="date-time", example="2022-12-17 08:44:09")
     *          )
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     *
     * @throws AuthorizationException
     */
    public function token(Request $request, int $id): Response
    {
        $impersonator = auth()->user();
        $targetWorker = $this->repository->find($id);

        $this->authorize('impersonate', $targetWorker);

        $expiredAt = CarbonImmutable::now()->add(config('auth.api_token_expires_in'));
        $targetToken = $targetWorker->createToken('api', expiresAt: $expiredAt);

        return response([
            'impersonator' => new AuthUserResource($impersonator),
            'impersonator_token' => $request->bearerToken(),
            'target_worker' => new AuthUserResource($targetWorker),
            'target_token' => $targetToken->plainTextToken,
            'target_token_expired_at' => $expiredAt->toDateTimeString(),
        ]);
    }
}
