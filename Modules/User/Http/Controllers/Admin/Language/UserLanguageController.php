<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin\Language;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\User\Http\Requests\Language\UserLanguageUpdateRequest;
use Modules\User\Repositories\UserRepository;

final class UserLanguageController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
    ) {
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UserLanguageUpdateRequest $request, int $id): void
    {
        $user = $this->userRepository->find($id);

        $this->authorize('update', $user);

        $ids = $request->collect('languages')->pluck('id')->filter()->unique();

        $user->languages()->sync($ids);
    }
}
