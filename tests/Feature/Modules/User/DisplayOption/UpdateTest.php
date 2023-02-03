<?php

namespace Tests\Feature\Modules\User\DisplayOption;

use App\Models\Model;
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

        $data = $requestData = DisplayOption::factory()->make([
            'user_id' => $this->getUser()->id,
        ])->toArray();

        $requestData['model_name'] = Model::query()->find($data['model_id'])->name;

        $response = $this->patch(
            route('admin.users.display-options.update', ['worker' => $this->getUser()->id, 'display_option' => $displayOption->id]),
            $requestData
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
}
