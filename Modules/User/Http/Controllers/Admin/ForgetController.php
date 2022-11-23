<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\User\Http\Requests\ForgetPasswordRequest;
use Modules\User\Http\Requests\ResetPasswordRequest;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;
use OpenApi\Annotations as OA;

final class ForgetController extends Controller
{
    /**
     * @OA\Post(
     *     path="/admin/auth/forget-password",
     *     tags={"Auth"},
     *     summary="Send Reset Password Notification",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  required={"email"},
     *                  type="object",
     *                  @OA\Property(property="email", format="email", type="string")
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=202,
     *          description="Accepted"
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
     * @param  ForgetPasswordRequest  $request
     * @param  UserRepository  $repository
     * @return Application|ResponseFactory|Response
     *
     * @throws ValidationException
     */
    public function forget(ForgetPasswordRequest $request, UserRepository $repository)
    {
        $user = $repository->findByField(['email' => $request->validated('email')])->first();

        if ($user->banned_at) {
            throw ValidationException::withMessages(['banned' => 'You have been banned']);
        }

        $status = Password::sendResetLink($request->only('email'));
        if ($status !== Password::RESET_LINK_SENT) {
            abort(Response::HTTP_BAD_REQUEST, $status);
        }

        return response($status, Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Post(
     *     path="/admin/auth/reset-password",
     *     tags={"Auth"},
     *     summary="Reset Password",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  type="object",
     *                  required={"email","password","password_confirmation","token"},
     *                  @OA\Property(property="email", format="email", type="string"),
     *                  @OA\Property(property="password", format="password", type="string"),
     *                  @OA\Property(property="password_confirmation", format="password", type="string"),
     *                  @OA\Property(property="token", type="string"),
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=202,
     *          description="Accepted"
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
     * @param  ResetPasswordRequest  $request
     * @return Application|Response|ResponseFactory
     */
    public function reset(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password) {
                if ($user->banned_at) {
                    throw ValidationException::withMessages(['banned' => 'You have been banned']);
                }

                $user->forceFill(['password' => Hash::make($password)])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            abort(Response::HTTP_BAD_REQUEST, $status);
        }

        return response($status, Response::HTTP_ACCEPTED);
    }
}
