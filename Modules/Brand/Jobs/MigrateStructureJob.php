<?php
declare(strict_types=1);

namespace Modules\Brand\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\Facades\Module;

class MigrateStructureJob implements ShouldQueue
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

        try {
            foreach ($this->modules as $module) {
                if (isset($appModules[$module])) {
                    $migrations = array_values(
                        array_diff(
                            scandir(base_path("/Modules/{$module}/Database/Migrations")),
                            ['..', '.']
                        ),
                    );

                    foreach ($migrations as $migration) {
                        Artisan::call(sprintf(
                            'brand-migrate --path=%s --database=%s',
                            "/Modules/{$module}/Database/Migrations/",
                            $this->db
                        ));
                    }
                }
            }
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
