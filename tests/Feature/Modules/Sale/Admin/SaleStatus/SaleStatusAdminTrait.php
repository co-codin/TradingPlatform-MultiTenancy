<?php

namespace Tests\Feature\Modules\Sale\Admin\SaleStatus;

use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\Artisan;

trait SaleStatusAdminTrait
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\App\Http\Middleware\SetTenant::class);
        if (RefreshDatabaseState::$migrated) {
            foreach ([
                /** List of migrations required for the tests */
                'Sale',
            ] as $migration) {
                Artisan::call('module:migrate ' . $migration . ' --seed');
            }
        }
    }
}
