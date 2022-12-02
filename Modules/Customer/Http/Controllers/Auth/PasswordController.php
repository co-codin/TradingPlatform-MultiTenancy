<?php

namespace Modules\Customer\Http\Controllers\Auth;

use App\Dto\Auth\PasswordResetDto;
use App\Http\Controllers\Controller;
use App\Services\Auth\PasswordService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\User\Models\User;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class PasswordController extends Controller
{
    /**
     * @param PasswordService $passwordService
     */
    public function __construct(
        protected PasswordService $passwordService,
    )
    {
    }

    /**
     * @return PasswordBroker
     */
    public function broker(): PasswordBroker
    {
        return Password::broker(config('auth.guards.web-customers.provider'));
    }

    /**
     * @param Request $request
     * @return Response
     * @throws UnknownProperties
     */
    public function reset(Request $request): Response
    {
        $status = $this->passwordService
            ->reset(
                new PasswordResetDto(
                    $request->only([
                        'email',
                        'password',
                        'password_confirmation',
                        'token',
                    ]),
                ),
            );

        return response($status, Response::HTTP_ACCEPTED);
    }
}
