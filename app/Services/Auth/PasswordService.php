<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Dto\Auth\PasswordResetDto;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

final class PasswordService
{
    /**
     * Reset password.
     *
     * @param PasswordResetDto $dto
     * @return mixed
     */
    public function reset(PasswordResetDto $dto): mixed
    {
        $status = Password::reset(
            [
                'email' => $dto->email,
                'password' => $dto->password,
                'password_confirmation' => $dto->password_confirmation,
                'token' => $dto->token,
            ],
            function (Authenticatable $user, $password) {
                if ($user->banned_at) {
                    throw ValidationException::withMessages(['banned' => 'You have been banned']);
                }

                $user->forceFill(['password' => Hash::make($password)])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
dd($status);
        if ($status !== Password::PASSWORD_RESET) {
            abort(ResponseAlias::HTTP_BAD_REQUEST, $status);
        }

        return $status;
    }
}
