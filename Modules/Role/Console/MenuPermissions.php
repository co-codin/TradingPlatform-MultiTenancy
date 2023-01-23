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
        foreach (UserMenu::getValues() as $permissionName) {
            Permission::query()
            ->updateOrCreate(
                [
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]
            );
        }

        $this->info('Created successfully');
    }

}
