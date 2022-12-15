<?php

namespace Tests\Feature\Modules\User\Brand;

use Modules\Brand\Models\Brand;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Tests\TestCase;

class ReadTest extends TestCase
{
    /**
     * @test
     */
    public function user_with_brand_can_view_any(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::VIEW_USERS));

        Brand::factory()
            ->create()
            ->users()
            ->sync($users = User::factory($count = 5)->create()->push($this->user));

        $response = $this->get(route('admin.users.index'));

        $response->assertOk();
        $this->assertCount($users->count(), $response['data']);

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
    public function can_not_view_any(): void
    {
        $this->authenticateUser();

        $response = $this->get(route('admin.users.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function user_with_brand_adn_permission_can_view(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::VIEW_USERS));

        Brand::factory()
            ->create()
            ->users()
            ->sync($users = User::factory(1)->create()->push($this->user));

        $response = $this->get(route('admin.users.show', ['worker' => $users->first()]));

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
    public function user_from_other_brand_cant_view(): void
    {
        $this->authenticateUser();

        Brand::factory()
            ->create()
            ->users()
            ->sync($user = User::factory()->create());

        $response = $this->get(route('admin.users.show', ['worker' => $user]));

        $response->assertForbidden();
    }
}
