<?php

namespace Modules\User\Http\Controllers\Admin;

use OpenApi\Annotations as OA;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Http\Resources\AuthUserResource;
use Modules\User\Models\User;

class AuthController extends Controller
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
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::query()
            ->where('email', $request->input('email'))
            ->first();

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
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response([], 200);
    }

    /**
     * @OA\Get(
     *     path="/admin/auth/user",
     *     tags={"Auth"},
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
    public function user()
    {
        return new AuthUserResource(auth()->user());
    }
}
