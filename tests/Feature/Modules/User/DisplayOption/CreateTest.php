<?php

namespace Tests\Feature\Modules\User\DisplayOption;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\User\Enums\UserDisplayOptionPermission;
use Modules\User\Models\DisplayOption;
use Modules\User\Models\User;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can create display option.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create(): void
    {
        $this->authenticateWithPermission(
            UserDisplayOptionPermission::fromValue(
                UserDisplayOptionPermission::CREATE_USER_DISPLAY_OPTIONS
            )
        );

        $data = DisplayOption::factory()->make(['user_id' => $this->getUser()->id])->toArray();

        $response = $this->post(route('admin.users.display-options.store', ['worker' => $this->getUser()->id]), $data);

        $response->assertCreated();

        $response->assertJson([
            'data' => [
                'model_id' => $data['model_id'],
                'user_id' => $data['user_id'],
                'name' => $data['name'],
                'columns' => $data['columns'],
            ],
        ]);
    }

    /**
     * Test unauthorized user can`t create display option.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_create(): void
    {
        $user = User::factory()->create();

        $data = DisplayOption::factory()->make(['user_id' => $user->id]);

        $response = $this->post(route('admin.users.display-options.store', ['worker' => $user->id]), $data->toArray());

        $response->assertUnauthorized();
    }

    /**
     * Test name required.
     *
     * @return void
     *
     * @test
     */
    public function name_is_required(): void
    {
        $this->authenticateWithPermission(
            UserDisplayOptionPermission::fromValue(
                UserDisplayOptionPermission::CREATE_USER_DISPLAY_OPTIONS
            )
        );

        $data = DisplayOption::factory()->make(['user_id' => $this->getUser()->id]);
        unset($data['name']);

        $response = $this->post(route('admin.users.display-options.store', ['worker' => $this->getUser()->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test model id required.
     *
     * @return void
     *
     * @test
     */
    public function model_id_is_required(): void
    {
        $this->authenticateWithPermission(
            UserDisplayOptionPermission::fromValue(
                UserDisplayOptionPermission::CREATE_USER_DISPLAY_OPTIONS
            )
        );

        $data = DisplayOption::factory()->make(['user_id' => $this->getUser()->id]);
        unset($data['model_id']);

        $response = $this->post(route('admin.users.display-options.store', ['worker' => $this->getUser()->id]), $data->toArray());

        $response->assertUnprocessable();
    }
}
