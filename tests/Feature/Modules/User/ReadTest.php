<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User;

use Modules\User\Models\User;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use HasAuth;
    use UsesMultitenancyConfig;

    /**
     * @test
     */
    public function admin_can_view_any()
    {
        User::factory(5)->create();

        $this->authenticateAdmin();

        $response = $this->get(route('admin.users.index'));

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'username',
                    'first_name',
                    'last_name',
                    'email',
                    'is_active',
                    'target',
                    '_lft',
                    '_rgt',
                    'parent_id',
                    'deleted_at',
                    'last_login',
                    'created_at',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function admin_can_view_any_with_relations()
    {
        User::factory(5)->create();

        $this->authenticateAdmin();

        $relations = [
            'roles',
            'roles.permissions',
            'parent',
            'ancestors',
            'descendants',
            'children',
            'brands',
            'displayOptions',
            'desks',
            'departments',
            'languages',
            'affiliate',
            'comProvider',
        ];

        $this->brand->makeCurrent();

        $this->withHeader('Tenant', $this->brand->database);

        $response = $this->get(route('admin.users.index', ['include' => implode(',', $relations)]));

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'username',
                    'first_name',
                    'last_name',
                    'email',
                    'is_active',
                    'target',
                    '_lft',
                    '_rgt',
                    'parent_id',
                    'deleted_at',
                    'last_login',
                    'created_at',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function admin_can_view(): void
    {
        $this->authenticateAdmin();

        $user = User::factory()->create();

        $response = $this->get(route('admin.users.show', ['worker' => $user]));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'username',
                'first_name',
                'last_name',
                'email',
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
    public function can_not_view(): void
    {
        $user = User::factory()->create();

        $this->authenticateUser();

        $response = $this->get(route('admin.users.show', ['worker' => $user]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function user_can_view_not_found(): void
    {
        $this->authenticateAdmin();

        $userId = User::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('admin.users.show', ['worker' => $userId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function not_unauthorized_view_any(): void
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('admin.users.show', ['worker' => $user]));

        $response->assertUnauthorized();
    }
}
