<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User;

use Illuminate\Support\Facades\Event;
use Modules\Role\Models\Role;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Modules\User\Models\WorkerInfo;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use HasAuth;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }

    /**
     * @test
     */
    public function can_create(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::CREATE_USERS));

        $data = User::factory()->withParent()
            ->withAffiliate()
            ->raw(['password' => self::$basePassword, 'is_active' => fake()->boolean]);

        $data['roles'] = [
            [
                'id' => Role::factory()->create()->id,
            ],
        ];

        $data['worker_info'] = WorkerInfo::factory()->raw();

        $this->brand->makeCurrent();

        Event::fake();

        $response = $this->post('/admin/workers', $data);

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'username',
                'is_active',
                'target',
                'parent_id',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    /**
     * @test
     */
    public function can_not_create(): void
    {
        $this->authenticateUser();

        $data = User::factory()->withParent()
            ->withAffiliate()
            ->raw(['password' => self::$basePassword, 'is_active' => fake()->boolean]);

        $data['roles'] = [
            [
                'id' => Role::factory()->create()->id,
            ],
        ];

        $data['worker_info'] = WorkerInfo::factory()->raw();

        $this->brand->makeCurrent();

        $response = $this->post('/admin/workers', $data);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post('/admin/workers');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
