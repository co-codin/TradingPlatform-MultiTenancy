<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User;

use Exception;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Modules\Role\Models\Permission;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Tests\TestCase;

final class UpdateBatchTest extends TestCase
{
    use DatabaseTransactions;

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
        $fakeUserData = User::factory(3)->withParent()->make();

        $data['users'] = [
            [
                'id' => $users[0]['id'],
                'username' => $fakeUserData[0]['username'],
                'first_name' => $fakeUserData[0]['first_name'],
                'last_name' => $fakeUserData[0]['last_name'],
            ],
            [
                'id' => $users[1]['id'],
                'email' => $fakeUserData[1]['email'],
                'confirm_password' => true,
                'password' => $fakeUserData[1]['password'],
            ],
            [
                'id' => $users[2]['id'],
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
                    'username' => $fakeUserData[0]['username'],
                    'first_name' => $fakeUserData[0]['first_name'],
                    'last_name' => $fakeUserData[0]['last_name'],
                ],
                [
                    'id' => $users[1]['id'],
                    'email' => $fakeUserData[1]['email'],
                ],
                [
                    'id' => $users[2]['id'],
                    'is_active' => $fakeUserData[2]['is_active'],
                    'target' => $fakeUserData[2]['target'],
                    'parent_id' => $fakeUserData[2]['parent_id'],
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

        $data['users'] = [
            [
                'id' => $users[0]['id'],
                'username' => $fakeUserData[0]['username'],
                'first_name' => $fakeUserData[0]['first_name'],
                'last_name' => $fakeUserData[0]['last_name'],
            ],
            [
                'id' => $users[1]['id'],
                'email' => $fakeUserData[1]['email'],
                'confirm_password' => true,
                'password' => $fakeUserData[1]['password'],
            ],
            [
                'id' => $users[2]['id'],
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

        $data['users'] = [
            [
                'id' => $users[0]['id'],
                'username' => $fakeUserData[0]['username'],
                'first_name' => $fakeUserData[0]['first_name'],
                'last_name' => $fakeUserData[0]['last_name'],
            ],
            [
                'id' => $users[1]['id'],
                'email' => $fakeUserData[1]['email'],
                'confirm_password' => true,
                'password' => $fakeUserData[1]['password'],
            ],
            [
                'id' => $users[2]['id'],
                'is_active' => $fakeUserData[2]['is_active'],
                'target' => $fakeUserData[2]['target'],
                'parent_id' => $fakeUserData[2]['parent_id'],
            ],
        ];

        $response = $this->patchJson(route('admin.users.batch.update'), $data);

        $response->assertUnauthorized();
    }
}
