<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\Worker\Models\Worker;
use Spatie\Permission\Traits\RefreshesPermissionCache;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase, RefreshesPermissionCache;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('optimize');
    }

    protected function authenticateWorker()
    {
        Worker::factory()->create([
            'email' => 'admin@admin.ru'
        ]);

        $response = $this->json('POST', route('admin.auth.login'), [
            'email' => 'admin@admin.ru',
            'password' => 'admin',
        ]);

        $this->withToken($response->json('token'));
    }

    protected function authenticateAdmin()
    {
        $worker = Worker::factory()->create([
            'email' => 'admin@admin.ru'
        ]);

        $role = Role::factory()->create([
            'name' => DefaultRole::ADMIN
        ]);

        $worker->roles()->sync($role);

        $response = $this->json('POST', route('admin.auth.login'), [
            'email' => 'admin@admin.ru',
            'password' => 'admin',
        ]);

        $this->withToken($response->json('token'));
    }
}

