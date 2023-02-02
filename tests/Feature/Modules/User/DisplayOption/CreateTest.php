<?php

namespace Tests\Feature\Modules\User\DisplayOption;

use App\Models\Model;
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

        $data = $requestData = DisplayOption::factory()->make([
            'user_id' => $this->getUser()->id,
        ])->toArray();

        $requestData['model_name'] = Model::query()->find($data['model_id'])->name;

        $response = $this->post(route('admin.users.display-options.store', ['worker' => $this->getUser()->id]), $requestData);

        $response->assertCreated();

        $response->assertJson([
            'data' => $data,
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
}
