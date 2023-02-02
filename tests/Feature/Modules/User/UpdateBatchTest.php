<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User;

use Exception;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Modules\User\Models\WorkerInfo;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdateBatchTest extends BrandTestCase
{
    use HasAuth;

    /**
     * Test authorized user can update batch.
     *
     * @test
     *
     * @return void
     *
     * @throws Exception
     */
    public function authorized_user_can_update_batch(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $users = User::factory(3)->create();
        $fakeUserData = User::factory(3)->withParent()->make(['password' => self::$basePassword])->toArray();

        $this->brand->makeCurrent();

        $data['users'] = [
            [
                'id' => $users[0]['id'],
                'worker_info' => WorkerInfo::factory()->raw(['user_id' => $users[0]['id']]),
                'username' => $fakeUserData[0]['username'],
            ],
            [
                'id' => $users[1]['id'],
                'worker_info' => WorkerInfo::factory()->raw(['user_id' => $users[1]['id']]),
                'confirm_password' => true,
                'password' => self::$basePassword,
            ],
            [
                'id' => $users[2]['id'],
                'worker_info' => WorkerInfo::factory()->raw(['user_id' => $users[2]['id']]),
                'is_active' => $fakeUserData[2]['is_active'],
                'target' => $fakeUserData[2]['target'],
                'parent_id' => $fakeUserData[2]['parent_id'],
            ],
        ];

        $response = $this->patchJson(route('admin.users.batch.update'), $data);

        $response->assertOk();

        $response->assertJson([
            'data' => [
                [
                    'id' => $users[0]['id'],
                ],
                [
                    'id' => $users[1]['id'],
                ],
                [
                    'id' => $users[2]['id'],
                ],
            ],
        ]);
    }

    /**
     * Test unauthorized user cant update batch.
     *
     * @test
     *
     * @return void
     *
     * @throws Exception
     */
    public function unauthorized_user_cant_update_batch(): void
    {
        $users = User::factory(3)->create();
        $fakeUserData = User::factory(3)->withParent()->make();

        $this->brand->makeCurrent();

        $data['users'] = [
            [
                'id' => $users[0]['id'],
                'worker_info' => WorkerInfo::factory()->raw(['user_id' => $users[0]['id']]),
                'username' => $fakeUserData[0]['username'],
            ],
            [
                'id' => $users[1]['id'],
                'worker_info' => WorkerInfo::factory()->raw(['user_id' => $users[1]['id']]),
                'confirm_password' => true,
                'password' => self::$basePassword,
            ],
            [
                'id' => $users[2]['id'],
                'worker_info' => WorkerInfo::factory()->raw(['user_id' => $users[2]['id']]),
                'is_active' => $fakeUserData[2]['is_active'],
                'target' => $fakeUserData[2]['target'],
                'parent_id' => $fakeUserData[2]['parent_id'],
            ],
        ];

        $response = $this->patchJson(route('admin.users.batch.update'), $data);

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user without permissions cant update batch.
     *
     * @test
     *
     * @return void
     *
     * @throws Exception
     */
    public function authorized_user_without_permissions_cant_update_batch(): void
    {
        $this->authenticateUser();

        $users = User::factory(3)->create();
        $fakeUserData = User::factory(3)->withParent()->make();

        $this->brand->makeCurrent();

        $data['users'] = [
            [
                'id' => $users[0]['id'],
                'worker_info' => WorkerInfo::factory()->raw(['user_id' => $users[0]['id']]),
                'username' => $fakeUserData[0]['username'],
            ],
            [
                'id' => $users[1]['id'],
                'worker_info' => WorkerInfo::factory()->raw(['user_id' => $users[1]['id']]),
                'confirm_password' => true,
                'password' => self::$basePassword,
            ],
            [
                'id' => $users[2]['id'],
                'worker_info' => WorkerInfo::factory()->raw(['user_id' => $users[2]['id']]),
                'is_active' => $fakeUserData[2]['is_active'],
                'target' => $fakeUserData[2]['target'],
                'parent_id' => $fakeUserData[2]['parent_id'],
            ],
        ];

        $response = $this->patchJson(route('admin.users.batch.update'), $data);

        $response->assertUnauthorized();
    }
}
