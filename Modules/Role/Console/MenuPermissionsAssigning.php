<?php

namespace Modules\Role\Console;

use Illuminate\Console\Command;
use Modules\Role\Models\Role;
use Modules\User\Enums\UserMenu;

class MenuPermissionsAssigning extends Command
{
    protected $signature = 'permission:menu-assigning';

    protected $description = 'Assigning Menu Permission to Role';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $roles = Role::get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name . ' / ' . $item->guard_name
            ];
        })->pluck('name', 'id')->toArray();

        $role = $this->choice('Please choice Role', $roles);

        $action = $this->choice('Please choice Action', ['give', 'revoke']);

        $permission = $this->choice('Please choice menu Permission', UserMenu::getValues());

        $roleData = array_map('trim', explode("/", $role));

        $roleModel = Role::whereName($roleData[0])->whereGuardName($roleData[1])->first();

        if ($action == 'give') {
            $roleModel->givePermissionTo($permission);
        } else {
            $roleModel->revokePermissionTo($permission);
        }

        $this->info('Successfully done: ' . $action . ' permission (' . $permission . ') to ' . $role);
    }
}
