<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin\Impersonate;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\User\Http\Resources\AuthUserResource;
use Modules\User\Repositories\UserRepository;

final class UserImpersonateController extends Controller
{
    public function __construct(
        protected UserRepository $repository
    ) {
    }

    /**
     * @throws AuthorizationException
     */
    public function token(Request $request, int $id): Response
    {
        $impersonator = auth()->user();
        $targetWorker = $this->repository->find($id);

        $this->authorize('impersonate', $targetWorker);

        $expiredAt = CarbonImmutable::now()->add(config('auth.api_token_expires_in'));
        $targetToken = $targetWorker->createToken('api', expiresAt: $expiredAt);

        return response([
            'data' => [
                'impersonator' => new AuthUserResource($impersonator),
                'impersonator_token' => $request->bearerToken(),
                'target_worker' => new AuthUserResource($targetWorker),
                'target_token' => $targetToken->plainTextToken,
            ],
        ]);
    }
}
