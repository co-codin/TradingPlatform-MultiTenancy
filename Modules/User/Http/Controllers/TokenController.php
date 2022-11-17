<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\User\Services\UserStorage;
use OpenApi\Annotations as OA;

final class TokenController extends Controller
{
    public function __construct(
        protected UserStorage $userStorage,
    ) {
    }

    /**
     * @OA\Post(
     *     path="/admin/token/create",
     *     tags={"Token"},
     *     security={ {"sanctum": {} }},
     *     summary="Create new token",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  required={
     *                      "token_name",
     *                  },
     *                  type="object",
     *                  @OA\Property(property="token_name", type="string")
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Schema (
     *                  required={
     *                      "token",
     *                  },
     *                  type="object",
     *                  @OA\Property(property="token", type="string")
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     *
     * @param  Request  $request
     * @return array
     *
     * @throws ValidationException
     */
    public function create(Request $request): array
    {
        $request->validate([
            'token_name' => 'required',
        ]);

        return ['token' => $request->user()->createToken($request->token_name)->plainTextToken];
    }

    /**
     * @OA\Delete(
     *     path="/admin/token/delete",
     *     tags={"Token"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete token",
     *     @OA\Response(
     *          response=200,
     *          description="success"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     )
     * )
     */
    public function delete(Request $request): void
    {
        $request->validate([
            'token_name' => 'required',
        ]);
        Auth::user()->tokens()->where('name', $request->token_name)->delete();
    }
}
