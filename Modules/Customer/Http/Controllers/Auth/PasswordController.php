<?php

namespace Modules\Customer\Http\Controllers\Auth;

use App\Dto\Auth\PasswordResetDto;
use App\Http\Controllers\Controller;
use App\Services\Auth\PasswordService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Customer\Http\Requests\PasswordResetRequest;
use Modules\User\Models\User;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
     * @OA\Post(
     *     path="/customers/reset-password",
     *     tags={"Customer"},
     *     summary="Reset password",
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
     *                  @OA\Property(property="send_email", type="bool"),
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent (
     *              type="object",
     *              @OA\Property(property="password", format="password", type="string"),
     *          ),
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
     * @param PasswordResetRequest $request
     * @return Response|JsonResponse
     * @throws UnknownProperties
     */
    public function reset(PasswordResetRequest $request): Response|JsonResponse
    {
        $status = $this->passwordService
            ->setBroker(config('auth.guards.web-customers.provider'))
            ->dispatchEvent($request->boolean('send_email'))
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

        return $request->boolean('send_email') ?
            response($status, ResponseAlias::HTTP_ACCEPTED) :
            response()->json([
                'data' => [
                    'password' => $request->get('password'),
                ],
            ]);
    }
}
