<?php

declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Arr;
use Modules\Customer\Models\Customer;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Permission;
use Modules\Role\Models\Role;
use Modules\User\Models\User;

trait HasAuth
{
    protected readonly Authenticatable $user;

    /**
     * Authenticate user.
     *
     * @param  string  $guard
     * @return void
     */
    final protected function authenticateUser(string $guard = User::DEFAULT_AUTH_GUARD): void
    {
        $email = 'user@service.com';

        $user = User::whereEmail($email)->first() ??
            User::factory()->create([
                'email' => $email,
            ]);

        $this->setUser($user);

        $this->actingAs($user, $guard);
    }

    /**
     * Authenticate customer.
     *
     * @param  string  $guard
     * @return void
     */
    final protected function authenticateCustomer(string $guard = Customer::DEFAULT_AUTH_GUARD): void
    {
        $this->brand?->makeCurrent();

        $email = 'customer@service.com';

        $user = Customer::whereEmail($email)->first() ??
            Customer::factory()->create([
                'email' => $email,
            ]);

        $this->setUser($user);

        $this->actingAs($user, $guard);
    }

    /**
     * Authenticate admin.
     *
     * @param  string  $guard
     * @return void
     */
    final protected function authenticateAdmin(string $guard = User::DEFAULT_AUTH_GUARD): void
    {
        $email = 'admin@service.com';

        $user = User::whereEmail($email)->first() ??
            User::factory()->create([
                'email' => $email,
            ]);

        $role = Role::where('name', DefaultRole::ADMIN)->first() ??
            Role::factory()->create([
                'name' => DefaultRole::ADMIN,
            ]);

        $user->roles()->sync($role);

        $this->setUser($user);

        $this->actingAs($user, $guard);
    }

    /**
     * Authenticate user with permission.
     *
     * @param  PermissionEnum  $permissionEnum
     * @param  string  $guard
     * @return void
     */
    final protected function authenticateWithPermission(
        PermissionEnum $permissionEnum,
        string $guard = User::DEFAULT_AUTH_GUARD
    ): void {
        $email = 'test@service.com';

        /** @var User $user */
        $user = User::whereEmail($email)->first() ??
            User::factory()->create([
                'email' => $email,
            ]);

        $permission = Permission::whereName($permissionEnum->value)->first() ??
            Permission::factory()->create([
                'name' => $permissionEnum->value,
                'guard_name' => $guard,
            ]);

        $user->permissions()->syncWithoutDetaching($permission);

        $this->setUser($user);

        $this->actingAs($user, $guard);
    }

    /**
     * Authenticate customer with permission.
     *
     * @param  PermissionEnum  $permissionEnum
     * @param  string  $guard
     * @return void
     */
    final protected function authenticateCustomerWithPermission(
        PermissionEnum $permissionEnum,
        string $guard = Customer::DEFAULT_AUTH_GUARD
    ): void {
        $this->brand?->makeCurrent();

        $email = 'test-customer@service.com';

        /** @var Customer $customer */
        if (! $customer = Customer::whereEmail($email)->first()) {
            $customer = Customer::factory()->make([
                'email' => $email,
            ]);

            $this->brand?->makeCurrent();
            $customer->save();
            $this->brand?->makeCurrent();
        }

        $permission = Permission::whereName($permissionEnum->value)->first() ??
            Permission::factory()->create([
                'name' => $permissionEnum->value,
                'guard_name' => $guard,
            ]);

        $customer->permissions()->syncWithoutDetaching($permission);

        $this->setUser($customer);

        $this->actingAs($customer, $guard);
    }

    final protected function addPermissions(array $permissionEnums): void
    {
        $user = $this->getUser();
        $permissions = [];

        foreach ($permissionEnums as $enum) {
            $permissions[] = Permission::whereName($enum->value)->first() ?? Permission::factory()->create([
                'name' => $enum->value,
            ]);
        }

        $user->permissions()->syncWithoutDetaching(Arr::pluck($permissions, 'id'));
    }

    /**
     * Set user.
     *
     * @param  Authenticatable  $user
     * @return $this
     */
    final protected function setUser(Authenticatable $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     */
    final protected function getUser(): User
    {
        return $this->user;
    }
}
