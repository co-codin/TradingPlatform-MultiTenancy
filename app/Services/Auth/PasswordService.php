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
    /**
     * @var string
     */
    protected string $broker;

    /**
     * @var bool
     */
    protected bool $dispatchEvent = true;

    public function __construct()
    {
        $this->broker = config('auth.guards.web.provider');
    }

    /**
     * Set broker.
     *
     * @param  string  $broker
     * @return $this
     */
    public function setBroker(string $broker): PasswordService
    {
        $this->broker = $broker;

        return $this;
    }

    /**
     * Without event dispatching.
     *
     * @param bool $value
     * @return $this
     */
    public function dispatchEvent(bool $value): PasswordService
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
                'password_confirmation' => $dto->password_confirmation,
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
}
