<?php

declare(strict_types=1);

namespace Modules\Brand\Jobs;

use App\Contracts\HasTenantDBConnection;
use App\Services\Tenant\Manager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Modules\Brand\Events\Tenant\BrandTenantIdentified;
use Modules\Brand\Models\Brand;
use Modules\Brand\Services\BrandDBService;

final class MigrateSchemaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected $brand,
    ) {
        $this->onQueue(Manager::TENANT_CONNECTION_NAME);
    }

    /**
     * @throws \Exception
     */
    public function handle(BrandDBService $brandDBService): void
    {
        $brandDBService
            ->setBrand($this->brand)
            ->setModules(BrandDBService::REQUIRED_MODULES)
            ->migrateDB()
            ->seedData();
    }

    /**
     * @param $module
     * @return string
     */
    private function prepareMigrations($module): string
    {
        $migrations = array_values(
            array_diff(
                scandir(base_path("Modules/{$module}/Database/Migrations")),
                ['..', '.']
            ),
        );

        return implode(' ', Arr::map($migrations, function ($migration) use ($module) {
            foreach (BrandDBService::ALLOWED_RELATIONS as $relation => $modules) {
                if (
                    stripos($migration, $relation) !== false
                    && ! in_array($modules, $this->modules, true)
                ) {
                    return false;
                }
            }

            foreach (BrandDBService::EXCEPT_MIGRATION_KEY_WORDS as $exceptKeyWord) {
                if (stripos($migration, $exceptKeyWord) !== false) {
                    return false;
                }
            }

            return '--path=' . base_path("Modules/{$module}/Database/Migrations/{$migration}");
        }));
    }
}
