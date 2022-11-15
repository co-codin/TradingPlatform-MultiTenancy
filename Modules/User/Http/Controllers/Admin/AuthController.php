<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use OpenApi\Annotations as OA;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Http\Resources\AuthUserResource;
use Modules\User\Models\User;

final class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/admin/auth/login",
     *     tags={"Auth"},
     *     summary="Auth User",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  type="object",
     *                  @OA\Property(property="email", type="string"),
     *                  @OA\Property(property="password", type="string")
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/AuthUserResponse")
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     *
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function login(Request $request): array
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::query()
            ->where('email', $request->input('email'))
            ->first();

        if ($user->banned_at) {
            throw ValidationException::withMessages([
                'message' => ['You have been banned'],
            ]);
        }

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'user' => new AuthUserResource($user),
            'token' => $user->createToken('api')->plainTextToken,
        ];
    }

    /**
     * @OA\Post(
     *     path="/admin/auth/logout",
     *     tags={"Auth"},
     *     security={ {"sanctum": {} }},
     *     summary="User Logout",
     *     @OA\Response(
     *          response=200,
     *          description="success"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     )
     * )
     *
     */
    public function logout(): void
    {
        Auth::user()->tokens()->delete();
    }

    /**
     * @OA\Get(
     *     path="/admin/auth/user",
     *     tags={"Auth"},
     *     security={ {"sanctum": {} }},
     *     summary="Authorized user data",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/AuthUserResource")
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     )
     * )
     *
     * @return AuthUserResource
     */
    public function user(): AuthUserResource
    {
        return new AuthUserResource(auth()->user());
    }
}
