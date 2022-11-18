<?php
declare(strict_types=1);

namespace Modules\Brand\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Modules\Brand\Services\BrandDBService;
use Nwidart\Modules\Facades\Module;

class MigrateStructureJob //implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use InteractsWithQueue;
    use SerializesModels;

    public function __construct(
        private readonly string $db,
        private readonly array $modules = []
    )
    {}

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        $appModules = Module::all();

        Artisan::call(sprintf(
            'migrate:install --database=%s',
            'tenant'
        ));

        try {
            foreach ($this->modules as $module) {
                if (isset($appModules[$module])) {
                    Artisan::call(sprintf(
                        'brand-migrate %s --database=%s',
                        $this->prepareMigrations($module),
                        'tenant'
                    ));
                }
            }
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
        dd("fine");
    }

    private function prepareMigrations($module)
    {
        $migrations = array_values(
            array_diff(
                scandir(base_path("/Modules/{$module}/Database/Migrations")),
                ['..', '.']
            ),
        );

        return implode(' ', Arr::map($migrations, function ($migration) use ($module) {
            foreach (BrandDBService::ALLOWED_RELATIONS as $relation => $modules) {
                if (
                    stripos($migration, $relation) !== false &&
                    ! in_array($modules, $this->modules)
                ) {
                    if (! in_array($modules, $this->modules)) {
                        return false;
                    }
                }
            }

            foreach (BrandDBService::EXCEPT_MIGRATION_KEY_WORDS as $exceptKeyWord) {
                if ( stripos($migration, $exceptKeyWord) !== false) {
                    return false;
                }
            }

            return '--path='.base_path("Modules/{$module}/Database/Migrations/{$migration}");
        }));
    }
}
