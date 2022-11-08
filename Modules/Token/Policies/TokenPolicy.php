<?php

namespace Modules\Token\Policies;

use App\Policies\BasePolicy;
use Modules\Token\Enums\TokenPermission;
use Modules\Token\Models\Token;
use Modules\User\Models\User;

class TokenPolicy extends BasePolicy
{
    public function viewAny(User $User): bool
    {
        return $User->can(TokenPermission::VIEW_TOKENS);
    }

    public function view(User $User, Token $token): bool
    {
        return $User->can(TokenPermission::VIEW_TOKENS);
    }

    public function create(User $User): bool
    {
        return $User->can(TokenPermission::CREATE_TOKENS);
    }

    public function update(User $User, Token $token): bool
    {
        return $User->can(TokenPermission::EDIT_TOKENS);
    }

    public function delete(User $User, Token $token): bool
    {
        return $User->can(TokenPermission::DELETE_TOKENS);
    }
}
