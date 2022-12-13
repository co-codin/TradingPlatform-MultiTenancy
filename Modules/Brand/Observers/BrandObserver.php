<?php

namespace Modules\Brand\Observers;

use Illuminate\Support\Facades\Artisan;
use Modules\Brand\Models\Brand;
use Illuminate\Support\Arr;

class BrandObserver
{/**
     * Handle the Brand "created" event.
     *
     * @param  Brand  $brand
     * @return void
     */
    public function created(Brand $brand)
    {
        $requiredModules = config('brandmigration.REQUIRED_MODULES');
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
     * @param  mixed $module
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
