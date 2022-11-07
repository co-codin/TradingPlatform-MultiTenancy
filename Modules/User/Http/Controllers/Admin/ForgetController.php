<?php
declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Modules\Worker\Http\Requests\ForgetPasswordRequest;
use Modules\Worker\Http\Requests\ResetPasswordRequest;

class ForgetController extends Controller
{
    public function forget(ForgetPasswordRequest $request)
    {
        $data = $request->validated();

        return Password::sendResetLink($$data) === Password::RESET_LINK_SENT ?
            abort(Response::HTTP_ACCEPTED) :
            abort(Response::HTTP_BAD_REQUEST);
    }

    public function reset(string $token, ResetPasswordRequest $request)
    {
        $data = $request->validated();

        $status = Password::reset(
            array_merge($data, ['token' => $token]),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET ?
            abort(Response::HTTP_ACCEPTED) :
            abort(Response::HTTP_BAD_REQUEST);
    }
}
