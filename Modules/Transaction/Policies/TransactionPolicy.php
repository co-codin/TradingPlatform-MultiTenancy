<?php

declare(strict_types=1);

namespace Modules\Transaction\Policies;

use App\Policies\BasePolicy;
use Modules\Transaction\Enums\TransactionPermission;
use Modules\User\Models\User;

final class TransactionPolicy extends BasePolicy
{
    /**
     * Export policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function export(User $user): bool
    {
        return $user->can(TransactionPermission::EXPORT_TRANSACTIONS);
    }
}
