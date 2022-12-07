<?php

namespace Modules\Role\Console;

use Illuminate\Console\Command;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Models\Action;
use Modules\Role\Models\Model;
use Modules\Role\Models\Permission;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

/**
 * Class SyncPermission
 */
class SyncPermissions extends Command
{
    protected $signature = 'permission:sync';

    protected $description = 'synchronize all permissions';

    public function handle()
    {
        $permissionFiles = Finder::create()
            ->in([
                base_path('Modules/*/Enums'),
                app_path('Enums'),
            ])
            ->name('/Permission.php$/')
            ->files();

        $availablePermissions = [];

        collect($permissionFiles)->map(function (SplFileInfo $file) {
            return '\\' . ucfirst(str_replace('/', '\\', str_replace(base_path() . '/', '', $file->getPath()))) . '\\' . $file->getBasename('.' . $file->getExtension());
        })
            ->filter(fn (string $class) => is_subclass_of($class, PermissionEnum::class))
            ->each(function ($enumClass) use (&$availablePermissions) {
                foreach ($enumClass::actions() as $value => $action) {
                    $availablePermissions[] = $value;

                    $action = Action::query()->firstOrCreate([
                        'name' => $action,
                    ]);

                    $model = Model::query()->firstOrCreate([
                        'name' => $enumClass::model(),
                    ]);

                    Permission::query()
                        ->updateOrCreate(
                            [
                                'action_id' => $action->id,
                                'model_id' => $model->id,
                            ],
                            [
                                'name' => $value,
                                'guard_name' => 'api',
                            ]
                        );
                }
            });
        Permission::query()
            ->whereNotIn('name', $availablePermissions)
            ->delete();
    }
}
