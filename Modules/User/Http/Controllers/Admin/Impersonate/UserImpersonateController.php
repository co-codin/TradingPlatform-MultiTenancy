<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin\Impersonate;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Modules\User\Http\Resources\AuthUserResource;
use Modules\User\Repositories\UserRepository;

class UserImpersonateController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
    ) {
    }

    public function update(int $id)
    {
        $impersonator = auth()->user();

        $this->authorize('impersonate', $impersonator);

        $newWorker = $this->userRepository->find($id);

        $expiredAt = CarbonImmutable::now()->add(config('auth.api_token_expires_in'));

        $newWorkerToken = $newWorker->createToken('api', expiresAt: $expiredAt);

        return response()->json([
            'data' => [
                'requested_worker' => new AuthUserResource($impersonator),
                'new_worker' => new AuthUserResource($newWorker),
                'new_token' => $newWorkerToken->plainTextToken,
            ],
        ]);
    }
}
