<?php

declare(strict_types=1);

namespace Modules\Customer\Policies;

use App\Policies\BasePolicy;
use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;

final class CustomerPolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(CustomerPermission::VIEW_CUSTOMERS);
    }

    /**
     * View policy.
     *
     * @param  User  $user
     * @param  Customer  $customer
     * @return bool
     */
    public function view(User $user, Customer $customer): bool
    {
        return $user->can(CustomerPermission::VIEW_CUSTOMERS);
    }

    /**
     * Create policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(CustomerPermission::CREATE_CUSTOMERS);
    }

    /**
     * Update policy.
     *
     * @param  User  $user
     * @param  Customer  $customer
     * @return bool
     */
    public function update(User $user, Customer $customer): bool
    {
        return match (true) {
            $user->id === $customer?->id => true,
            $user->can(CustomerPermission::EDIT_CUSTOMERS) => true,
            default => false,
        };
    }

    /**
     * Delete policy.
     *
     * @param  User  $user
     * @param  Customer  $customer
     * @return bool
     */
    public function delete(User $user, Customer $customer): bool
    {
        return $user->can(CustomerPermission::DELETE_CUSTOMERS);
    }

    public function impersonate(User $user, Customer $customer): bool
    {
        return $user->can(CustomerPermission::IMPERSONATE_CUSTOMERS);
    }

    /**
     * Reset password customers policy.
     *
     * @param  User  $user
     * @param  Customer  $customer
     * @return bool
     */
    public function resetPassword(User $user, Customer $customer): bool
    {
        return $user->can(CustomerPermission::EDIT_CUSTOMERS);
    }

    /**
     * Ban customer policy.
     *
     * @param  User  $user
     * @param  Customer  $customer
     * @return bool
     */
    public function ban(User $user, Customer $customer): bool
    {
        return $user->can(CustomerPermission::BAN_CUSTOMERS);
    }

    /**
     * Unban customer policy.
     *
     * @param  User  $user
     * @param  Customer  $customer
     * @return bool
     */
    public function unban(User $user, Customer $customer): bool
    {
        return $user->can(CustomerPermission::BAN_CUSTOMERS);
    }

    /**
     * Export customers policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function export(User $user): bool
    {
        return $user->can(CustomerPermission::EXPORT_CUSTOMERS);
    }

    /**
     * Import customers policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function import(User $user): bool
    {
        return $user->can(CustomerPermission::IMPORT_CUSTOMERS);
    }

    /**
     * View any by affiliate policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAnyByAffiliate(User $user): bool
    {
        return $user->isAffiliate() && $user->can(CustomerPermission::VIEW_CUSTOMERS);
    }

    /**
     * View by affiliate policy.
     *
     * @param  User  $user
     * @param  Customer  $customer
     * @return bool
     */
    public function viewByAffiliate(User $user, Customer $customer): bool
    {
        return $user->isAffiliate() && $user->can(CustomerPermission::VIEW_CUSTOMERS);
    }
}
