<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     *                  type="object",
     *                  required={
     *                      "token_name",
     *                  },
     *                  @OA\Property(property="token_name", type="string")
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              type="object",
     *              required={"token"},
     *              @OA\Property(property="token", type="string")
     *          )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     *
     * @param  Request  $request
     * @return Response
     *
     * @throws ValidationException
     */
    public function create(Request $request)
    {
        $request->validate([
            'token_name' => 'required',
        ]);

        return response([
            'token' => $request->user()->createToken($request->token_name)->plainTextToken,
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Delete(
     *     path="/admin/token/delete",
     *     tags={"Token"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete token",
     *     @OA\Response(
     *          response=204,
     *          description="No content"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not found"
     *     ),
     *     @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *     ),
     * )
     *
     * @param  Request  $request
     * @return Response
     */
    public function delete(Request $request): Response
    {
        $request->validate(['token_name' => 'required']);
        $token = Auth::user()->tokens()->where('name', $request->token_name)->firstOrFail();
        $token->delete();

        return response()->noContent();
    }
}
