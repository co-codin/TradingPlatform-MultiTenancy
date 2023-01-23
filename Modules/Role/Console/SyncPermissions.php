<?php

declare(strict_types=1);

namespace Modules\Role\Console;

use App\Models\Action;
use App\Models\Model;
use Illuminate\Console\Command;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Models\Permission;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

/**
 * Class SyncPermission
 */
final class SyncPermissions extends Command
{
    protected $signature = 'permission:sync';

    protected $description = 'synchronize all permissions';

    public function handle(): void
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
            return '\\' . ucfirst(
                str_replace('/', '\\', str_replace(base_path() . '/', '', $file->getPath()))
            ) . '\\' . $file->getBasename('.' . $file->getExtension());
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
                                'guard_name' => 'web',
                            ]
                        );
                }
            });
        Permission::query()
            ->whereNotIn('name', $availablePermissions)
            ->delete();

        $this->call('permission:menu');
    }
}
