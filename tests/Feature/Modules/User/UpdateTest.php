<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User;

use Illuminate\Support\Facades\Event;
use Modules\Brand\Models\Brand;
use Modules\Role\Models\Role;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Modules\User\Models\WorkerInfo;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;
use Throwable;

final class UpdateTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function admin_can_update(): void
    {
        try {
            $this->authenticateAdmin();

            $user = User::factory()->create();
            $data = User::factory()->withParent()
                ->withAffiliate()
                ->raw(['password' => self::$basePassword, 'is_active' => fake()->boolean]);

            $data['roles'] = [
                [
                    'id' => Role::factory()->create()->id,
                ],
            ];

            $data['change_password'] = true;
            $data['worker_info'] = WorkerInfo::factory()->raw();

            $this->brand->makeCurrent();

            Event::fake();

            $response = $this->patch(route('admin.users.update', ['worker' => $user]), $data);

            $response->assertOk();
            $response->assertJsonStructure([
                'data' => [
                    'id',
                    'username',
                    'is_active',
                    'target',
                    '_lft',
                    '_rgt',
                    'parent_id',
                    'deleted_at',
                    'last_login',
                    'created_at',
                ],
            ]);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function user_with_brand_and_permission_can_update(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        (Brand::factory()
            ->create()
            ->makeCurrent())
            ->users()
            ->sync($users = User::factory(1)->create()->push($this->user));

        $data = User::factory()->withParent()
            ->withAffiliate()
            ->raw(['password' => self::$basePassword, 'is_active' => fake()->boolean]);

        $data['roles'] = [
            [
                'id' => Role::factory()->create()->id,
            ],
        ];

        $data['change_password'] = true;
        $data['worker_info'] = WorkerInfo::factory()->raw();

        $this->brand->makeCurrent();

        Event::fake();

        $response = $this->patch(route('admin.users.update', ['worker' => $users->first()]), $data);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'username',
                'is_active',
                'target',
                '_lft',
                '_rgt',
                'parent_id',
                'deleted_at',
                'last_login',
                'created_at',
            ],
        ]);
    }

    /**
     * @test
     */
    public function user_from_other_brand_cant_update(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        (Brand::factory()
            ->create()
            ->makeCurrent())
            ->users()
            ->sync($user = User::factory()->create());

        $data = User::factory()->withParent()
            ->withAffiliate()
            ->raw(['password' => self::$basePassword, 'is_active' => fake()->boolean]);

        $data['roles'] = [
            [
                'id' => Role::factory()->create()->id,
            ],
        ];

        $data['change_password'] = true;
        $data['worker_info'] = WorkerInfo::factory()->raw();

        $this->brand->makeCurrent();

        Event::fake();

        $response = $this->patch(route('admin.users.update', ['worker' => $user]), $data);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateAdmin();

        $data = User::factory()->withParent()
            ->withAffiliate()
            ->raw(['password' => self::$basePassword, 'is_active' => fake()->boolean]);

        $data['roles'] = [
            [
                'id' => Role::factory()->create()->id,
            ],
        ];

        $data['change_password'] = true;
        $data['worker_info'] = WorkerInfo::factory()->raw();

        $this->brand->makeCurrent();

        Event::fake();

        $userId = User::orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->patch(route('admin.users.update', ['worker' => $userId]), $data);

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $user = User::factory()->create();
        $data = User::factory()->withParent()
            ->withAffiliate()
            ->raw(['password' => self::$basePassword, 'is_active' => fake()->boolean]);

        $data['roles'] = [
            [
                'id' => Role::factory()->create()->id,
            ],
        ];

        $data['change_password'] = true;
        $data['worker_info'] = WorkerInfo::factory()->raw();

        $this->brand->makeCurrent();

        Event::fake();

        $response = $this->patch(route('admin.users.update', ['worker' => $user]), $data);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $user = User::factory()->create();

        $response = $this->patch(route('admin.users.update', ['worker' => $user]));

        $response->assertUnauthorized();
    }
}
