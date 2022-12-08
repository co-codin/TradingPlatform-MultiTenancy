<?php

namespace App\Observers;

use Illuminate\Support\Facades\Artisan;
use Modules\Brand\Models\Brand;
use Illuminate\Support\Arr;

class BrandObserver
{
    /**
     * Handle the Brand "creating" event.
     *
     * @param  Brand  $brand
     * @return void
     */
    public function creating(Brand $brand)
    {
        //
    }

    /**
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
     * Handle the Brand "updated" event.
     *
     * @param  Brand  $brand
     * @return void
     */
    public function updated(Brand $brand)
    {
        //
    }

    /**
     * Handle the Brand "deleted" event.
     *
     * @param  Brand  $brand
     * @return void
     */
    public function deleted(Brand $brand)
    {
        //
    }

    /**
     * Handle the Brand "restored" event.
     *
     * @param  Brand  $brand
     * @return void
     */
    public function restored(Brand $brand)
    {
        //
    }

    /**
     * Handle the Brand "force deleted" event.
     *
     * @param  Brand  $brand
     * @return void
     */
    public function forceDeleted(Brand $brand)
    {
        //
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
