<?php

namespace Modules\User\Http\Controllers\Admin\Language;

use App\Http\Controllers\Controller;
use Modules\User\Http\Requests\Language\UserLanguageUpdateRequest;
use Modules\User\Repositories\UserRepository;

class UserLanguageController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function update(UserLanguageUpdateRequest $request, int $user)
    {
        $user = $this->userRepository->find($user);

        $this->authorize('update', $user);

        $ids = $request->get('languages')->pluck('id')->filter()->unique();

        $user->languages()->sync($ids);
    }
}
