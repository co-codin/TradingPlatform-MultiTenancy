<?php

namespace Modules\Customer\Http\Controllers\Admin\Auth;

use App\Dto\Auth\PasswordResetDto;
use App\Http\Controllers\Controller;
use App\Services\Auth\PasswordService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Modules\Customer\Http\Requests\PasswordResetRequest;
use Modules\Customer\Repositories\CustomerRepository;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PasswordController extends Controller
{
    /**
     * @param  PasswordService  $passwordService
     * @param  CustomerRepository  $customerRepository
     */
    public function __construct(
        protected PasswordService $passwordService,
        protected CustomerRepository $customerRepository,
    ) {
    }

    /**
     * @OA\Post(
     *     path="/admin/customers/{id}/reset-password",
     *     tags={"Customer"},
     *     summary="Reset password",
     *     @OA\Parameter(
     *         description="Customer ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
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
     * @param  PasswordResetRequest  $request
     * @param  int  $customer
     * @return Response|JsonResponse
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function reset(PasswordResetRequest $request, int $customer): Response|JsonResponse
    {
        $customer = $this->customerRepository->find($customer);

        $this->authorize('resetPassword', $customer);

        $status = $this->passwordService
            ->dispatchEvent($request->boolean('send_email'))
            ->reset(
                new PasswordResetDto([
                    'email' => $customer->email,
                    'password' => $request->validated('password'),
                    'token' => Password::createToken($customer),
                ]),
            );

        return $request->boolean('send_email') ?
            response($status, ResponseAlias::HTTP_ACCEPTED) :
            response()->json([
                'data' => [
                    'password' => $request->validated('password'),
                ],
            ]);
    }
}
