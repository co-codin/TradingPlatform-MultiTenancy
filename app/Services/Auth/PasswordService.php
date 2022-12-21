<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Dto\Auth\PasswordResetDto;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

final class PasswordService
{
    private readonly string $broker;

    private bool $dispatchEvent = true;

    public function __construct(string $guard)
    {
        $this->broker = config("auth.guards.{$guard}.provider");
    }

    /**
     * Without event dispatching.
     *
     * @param  bool  $value
     * @return $this
     */
    public function dispatchEvent(bool $value): self
    {
        $this->dispatchEvent = $value;

        return $this;
    }

    /**
     * Reset password.
     *
     * @param  PasswordResetDto  $dto
     * @return mixed
     */
    public function reset(PasswordResetDto $dto): mixed
    {
        $status = Password::broker($this->broker)->reset(
            [
                'email' => $dto->email,
                'password' => $dto->password,
                'token' => $dto->token,
            ],
            function (Authenticatable $user, $password) {
                if ($user->banned_at) {
                    throw ValidationException::withMessages(['banned' => 'You have been banned']);
                }

                $user->forceFill(['password' => Hash::make($password)])->setRememberToken(Str::random(60));

                $user->save();

                if ($this->dispatchEvent) {
                    event(new PasswordReset($user));
                }
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            abort(ResponseAlias::HTTP_BAD_REQUEST, $status);
        }

        return $status;
    }

    public function sendResetLink(array $credentials): string
    {
        return Password::broker($this->broker)->sendResetLink($credentials);
    }
}
