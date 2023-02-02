<?php

declare(strict_types=1);

namespace Modules\Brand\Observers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Modules\Brand\Models\Brand;

final class BrandObserver
{
    /**
     * Handle the Brand "created" event.
     *
     * @param  Brand  $brand
     * @return void
     */
    public function created(Brand $brand): void
    {
        $requiredModules = Config::get('multitenancy.tenant_required_migrations');

        foreach ($requiredModules as $module) {
            Artisan::call(sprintf(
                'tenants:artisan "migrate %s --database=tenant" --tenant=%s',
                $this->prepareMigrations($module),
                $brand->id
            ));
        }

        foreach ($requiredModules as $module) {
            Artisan::call(sprintf(
                'tenants:artisan "module:seed %s --database=tenant" --tenant=%s',
                $module,
                $brand->id
            ));
        }
    }

    /**
     * Prepare migrations.
     *
     * @param  string  $module
     * @return string
     */
    private function prepareMigrations(string $module): string
    {
        $path = "Modules/{$module}/Database/Migrations/Tenant";

        $migrations = array_values(
            array_diff(
                scandir(base_path($path)),
                ['..', '.']
            ),
        );

        return implode(' ', Arr::map($migrations, function ($migration) use ($path) {
            return "--path={$path}/{$migration}";
        }));
    }
}
