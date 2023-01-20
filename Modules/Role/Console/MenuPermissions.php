<?php

declare(strict_types=1);

namespace Modules\Role\Console;

use Illuminate\Console\Command;
use Modules\Role\Models\Permission;
use Modules\User\Enums\UserMenu;

final class MenuPermissions extends Command
{
    protected $signature = 'permission:menu';

    protected $description = 'Create permissions for menu';

    public function handle(): void
    {
        $permission = $this->choice('Please choice menu permission', array_merge(UserMenu::getValues(), ['all' => 'Add all permissions']));

        if ($permission == 'all') {
            foreach (UserMenu::getValues() as $permissionKey => $permissionName) {
                $this->createPermission($permissionKey);
            }
        } else {
            $this->createPermission($permission);
        }
    }

    private function createPermission($permission): void
    {
        $permissionLists = UserMenu::getValues();

        $permissionModel = Permission::query()
            ->updateOrCreate(
                [
                    'name' => $permissionLists[$permission],
                    'guard_name' => 'web',
                ]
            );
        if ($permissionModel->wasRecentlyCreated) {
            $this->info($permissionLists[$permission] . ': created successfully');
        } else {
            $this->error($permissionLists[$permission] . ': already exists');
        }
    }
}
