<?php

declare(strict_types=1);

namespace Modules\Brand\Jobs;

use App\Contracts\HasTenantDBConnection;
use App\Services\Tenant\Manager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Modules\Brand\Events\Tenant\BrandTenantIdentified;
use Modules\Brand\Services\BrandDBService;

final class MigrateStructureJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use InteractsWithQueue;

    /**
     * @param HasTenantDBConnection $tenant
     * @param array $modules
     * @param array $availableModules
     * @param string $connectionName
     */
    public function __construct(
        private readonly HasTenantDBConnection $tenant,
        private readonly array $modules = [],
        private readonly array $availableModules = [],
        private readonly string $connectionName = Manager::TENANT_CONNECTION_NAME,
    ) {
        $this->onQueue($connectionName);
    }

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        BrandTenantIdentified::dispatch($this->tenant);

        if (! Schema::connection($this->connectionName)->hasTable('migrations')) {
            Artisan::call(sprintf(
                'migrate:install --database=%s',
                $this->connectionName
            ));
        }

        $forMigrate = array_values(array_diff($this->modules, $this->brand->tables ?? []));
        $forRollback = array_values(array_diff($this->brand->tables ?? [], $this->modules));

        foreach ($forRollback as $module) {
            Artisan::call(sprintf(
                'brand:migrate-rollback %s --database=%s',
                $this->prepareMigrations($module),
                $this->connectionName
            ));
        }

        foreach ($forMigrate as $module) {
            if (in_array($module, $this->availableModules)) {
                Artisan::call(sprintf(
                    'brand:migrate %s --database=%s',
                    $this->prepareMigrations($module),
                    $this->connectionName
                ));
            }
        }
    }

    /**
     * @param $module
     * @return string
     */
    private function prepareMigrations($module): string
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
