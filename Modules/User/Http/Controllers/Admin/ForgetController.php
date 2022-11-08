<?php
declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Modules\User\Http\Requests\ForgetPasswordRequest;
use Modules\User\Http\Requests\ResetPasswordRequest;

class ForgetController extends Controller
{
    /**
     * @OA\Post(
     *     path="/admin/auth/forget-password",
     *     tags={"Forget"},
     *     summary="Send Reset Password Token",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  type="object",
     *                  @OA\Property(property="email", type="string")
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=202,
     *          description="success"
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *     )
     * )
     *
     * @param ForgetPasswordRequest $request
     * @return never
     */
    public function forget(ForgetPasswordRequest $request)
    {
        $data = $request->validated();

        return Password::sendResetLink($data) === Password::RESET_LINK_SENT ?
            abort(Response::HTTP_ACCEPTED) :
            abort(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @OA\Post(
     *     path="/admin/auth/reset-password/{token}",
     *     tags={"Forget"},
     *     summary="Reset Password Link",
     *     @OA\Parameter(
     *          name="token",
     *          in="path",
     *          description="Reset Password Token",
     *          @OA\Schema (
     *              type="string"
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  type="object",
     *                  @OA\Property(property="email", type="string"),
     *                  @OA\Property(property="password", type="string"),
     *                  @OA\Property(property="password_confirmation", type="string")
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=202,
     *          description="success"
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *     )
     * )
     *
     * @param string $token
     * @param ResetPasswordRequest $request
     * @return never
     */
    public function reset(string $token, ResetPasswordRequest $request)
    {
        $request->validated();

        $status = Password::reset(
            array_merge($request->only('email', 'password', 'password_confirmation'), ['token' => $token]),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET ?
            abort(Response::HTTP_ACCEPTED, $status) :
            abort(Response::HTTP_BAD_REQUEST, $status);
    }
}
