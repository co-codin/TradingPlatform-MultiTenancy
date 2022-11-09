<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\User\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    final protected function authenticateUser(): void
    {
        User::factory()->create([
            'email' => 'test@service.com',
        ]);

        $response = $this->post('/admin/auth/login', [
            'email' => 'test@service.com',
            'password' => 'admin',
        ]);

        $this->withToken($response->json('token'));
    }

    final protected function authenticateAdmin(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@service.com',
        ]);

        $role = Role::factory()->create([
            'name' => DefaultRole::ADMIN,
        ]);

        $user->roles()->sync($role);

        $response = $this->post('/admin/auth/login', [
            'email' => 'admin@service.com',
            'password' => 'admin',
        ]);

        $this->withToken($response->json('token'));
    }
}

