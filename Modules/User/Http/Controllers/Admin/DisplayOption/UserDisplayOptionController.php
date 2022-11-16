<?php

namespace Modules\User\Http\Controllers\Admin\DisplayOption;

use App\Http\Controllers\Controller;
use Modules\User\Http\Requests\DisplayOption\UserDisplayOptionCreateRequest;
use Modules\User\Http\Requests\DisplayOption\UserDisplayOptionUpdateRequest;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\UserDisplayOptionService;

class UserDisplayOptionController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository,
        protected UserDisplayOptionService $userDisplayOptionService
    ) {}

    public function store(UserDisplayOptionCreateRequest $request)
    {

    }

    public function update(UserDisplayOptionUpdateRequest $request, int $user)
    {

    }

    public function destroy(int $user)
    {

    }
}
