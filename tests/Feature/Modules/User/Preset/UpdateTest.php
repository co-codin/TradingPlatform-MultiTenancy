<?php

namespace Tests\Feature\Modules\User\DisplayOption;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\User\Enums\UserDisplayOptionPermission;
use Modules\User\Models\DisplayOption;
use Modules\User\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can update display option.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_update(): void
    {
        $this->authenticateWithPermission(
            UserDisplayOptionPermission::fromValue(
                UserDisplayOptionPermission::EDIT_USER_DISPLAY_OPTIONS
            )
        );

        $displayOption = DisplayOption::factory()->create(['user_id' => $this->getUser()->id]);
        $data = DisplayOption::factory()->make(['user_id' => $this->getUser()->id]);

        $response = $this->patch(
            route('admin.users.display-options.update', ['worker' => $this->getUser()->id, 'display_option' => $displayOption->id]),
            $data->toArray()
        );

        $response->assertOk();

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
     * Test unauthorized user can`t update display option.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_update(): void
    {
        $user = User::factory()->create();
        $displayOption = DisplayOption::factory()->create(['user_id' => $user->id]);
        $data = DisplayOption::factory()->make(['user_id' => $user->id]);

        $response = $this->patch(
            route('admin.users.display-options.update', ['worker' => $user->id, 'display_option' => $displayOption->id]),
            $data->toArray()
        );

        $response->assertUnauthorized();
    }

    /**
     * Test name filled.
     *
     * @return void
     *
     * @test
     */
    public function name_is_filled(): void
    {
        $this->authenticateWithPermission(
            UserDisplayOptionPermission::fromValue(
                UserDisplayOptionPermission::EDIT_USER_DISPLAY_OPTIONS
            )
        );

        $displayOption = DisplayOption::factory()->create(['user_id' => $this->getUser()->id]);
        $data = DisplayOption::factory()->make(['user_id' => $this->getUser()->id, 'name' => null]);

        $response = $this->patch(
            route('admin.users.display-options.update', ['worker' => $this->getUser()->id, 'display_option' => $displayOption->id]),
            $data->toArray()
        );

        $response->assertUnprocessable();
    }

    /**
     * Test model id filled.
     *
     * @return void
     *
     * @test
     */
    public function model_id_is_filled(): void
    {
        $this->authenticateWithPermission(
            UserDisplayOptionPermission::fromValue(
                UserDisplayOptionPermission::EDIT_USER_DISPLAY_OPTIONS
            )
        );

        $displayOption = DisplayOption::factory()->create(['user_id' => $this->getUser()->id]);
        $data = DisplayOption::factory()->make(['user_id' => $this->getUser()->id, 'model_id' => null]);

        $response = $this->patch(
            route('admin.users.display-options.update', ['worker' => $this->getUser()->id, 'display_option' => $displayOption->id]),
            $data->toArray()
        );

        $response->assertUnprocessable();
    }

    /**
     * Test name is string.
     *
     * @return void
     *
     * @test
     */
    public function name_is_string(): void
    {
        $this->authenticateWithPermission(
            UserDisplayOptionPermission::fromValue(
                UserDisplayOptionPermission::EDIT_USER_DISPLAY_OPTIONS
            )
        );

        $displayOption = DisplayOption::factory()->create(['user_id' => $this->getUser()->id]);
        $data = DisplayOption::factory()->make(['user_id' => $this->getUser()->id, 'name' => null]);
        $data->name = 1;

        $response = $this->patch(
            route('admin.users.display-options.update', ['worker' => $this->getUser()->id, 'display_option' => $displayOption->id]),
            $data->toArray()
        );

        $response->assertUnprocessable();
    }

    /**
     * Test model id is integer.
     *
     * @return void
     *
     * @test
     */
    public function model_id_is_integer(): void
    {
        $this->authenticateWithPermission(
            UserDisplayOptionPermission::fromValue(
                UserDisplayOptionPermission::EDIT_USER_DISPLAY_OPTIONS
            )
        );

        $displayOption = DisplayOption::factory()->create(['user_id' => $this->getUser()->id]);
        $data = DisplayOption::factory()->make(['user_id' => $this->getUser()->id, 'model_id' => null]);
        $data->model_id = 'string';

        $response = $this->patch(
            route('admin.users.display-options.update', ['worker' => $this->getUser()->id, 'display_option' => $displayOption->id]),
            $data->toArray()
        );

        $response->assertUnprocessable();
    }
}
