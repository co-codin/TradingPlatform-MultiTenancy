<?php

namespace Tests\Feature\Modules\Customer\Admin;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\Artisan;

trait CustomerAdminTrait
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\App\Http\Middleware\SetTenant::class);
        if (RefreshDatabaseState::$migrated) {
            foreach ([
                /** List of migrations required for the tests */
                'Geo',
                'Language',
                'Desk',
                'Department',
                'Campaign',
                'Sale',
                'Customer'
            ] as $migration) {
                Artisan::call('module:migrate ' . $migration . ' --seed');
            }
        }
    }
}
