<?php

namespace Modules\Brand\Observers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Modules\Brand\Models\Brand;

class BrandObserver
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

            Artisan::call(sprintf(
                'tenants:artisan "module:seed %s --database=tenant" --tenant=%s',
                $module,
                $brand->id
            ));
        }
    }

    /**
     * prepareMigrations
     *
     * @param  mixed  $module
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
            return "--path=Modules/{$module}/Database/Migrations/{$migration}";
        }));
    }
}
