<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Models\Action;
use App\Models\Model;
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
    use HasCustomerAuth;

    /**
     * @var string
     */
    public static string $basePassword = 'Password%12345';

    protected readonly Authenticatable $user;

    /**
     * Authenticate user.
     *
     * @param  string  $guard
     * @return void
     */
    final protected function authenticateUser(string $guard = User::DEFAULT_AUTH_GUARD): void
    {
        $user = $this->getUserByUsername('user@service.com');

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
        $user = $this->getUserByUsername('admin@service.com');

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
        $user = $this->getUserByUsername('test@service.com');

        $permissions = Permission::whereName($permissionEnum->value)->get();
        $permissions = ! $permissions->isEmpty()
            ? $permissions
            : Permission::create([
                'name' => $permissionEnum->value,
                'action_id' => Action::where('name', $permissionEnum::actions()[$permissionEnum->value])->first()?->id
                    ?? Action::factory()->create(['name' => $permissionEnum::actions()[$permissionEnum->value]])->id,
                'model_id' => Model::where('name', trim($permissionEnum::model(), '\\'))->first()?->id
                    ?? Model::factory()->create(['name' => trim($permissionEnum::model(), '\\')])->id,
                'guard_name' => $guard,
            ]);

        $user->permissions()->syncWithoutDetaching($permissions);

        $this->setUser($user);

        $this->actingAs($user, $guard);
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
     * Get user by login.
     *
     * @param string $username
     * @return User
     */
    private function getUserByUsername(string $username): User
    {
        return User::where('username', $username)->first() ??
            User::factory()->create([
                'username' => $username,
            ]);
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
