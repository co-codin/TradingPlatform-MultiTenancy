<?php

namespace Modules\Token\Policies;

use App\Policies\BasePolicy;
use Modules\Token\Enums\TokenPermission;
use Modules\Token\Models\Token;
use Modules\Worker\Models\Worker;

class TokenPolicy extends BasePolicy
{
    public function viewAny(Worker $worker): bool
    {
        return $worker->can(TokenPermission::VIEW_TOKENS);
    }

    public function view(Worker $worker, Token $token): bool
    {
        return $worker->can(TokenPermission::VIEW_TOKENS);
    }

    public function create(Worker $worker): bool
    {
        return $worker->can(TokenPermission::CREATE_TOKENS);
    }

    public function update(Worker $worker, Token $token): bool
    {
        return $worker->can(TokenPermission::EDIT_TOKENS);
    }

    public function delete(Worker $worker, Token $token): bool
    {
        return $worker->can(TokenPermission::DELETE_TOKENS);
    }
}
