<?php

namespace Tests\Feature\Modules\User\Preset;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\User\Enums\UserPresetPermission;
use Modules\User\Models\Preset;
use Modules\User\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function authorized_user_can_update(): void
    {
        $this->authenticateWithPermission(
            UserPresetPermission::fromValue(
                UserPresetPermission::EDIT_USER_PRESETS
            )
        );

        $preset = Preset::factory()->create(['user_id' => $this->getUser()->id]);
        $data = Preset::factory()->make(['user_id' => $this->getUser()->id]);

        $response = $this->patch(
            route('admin.users.presets.update', ['worker' => $this->getUser()->id, 'preset' => $preset->id]),
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
     * @test
     */
    public function unauthorized_user_cant_update(): void
    {
        $user = User::factory()->create();
        $preset = Preset::factory()->create(['user_id' => $user->id]);
        $data = Preset::factory()->make(['user_id' => $user->id]);

        $response = $this->patch(
            route('admin.users.presets.update', ['worker' => $user->id, 'preset' => $preset->id]),
            $data->toArray()
        );

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function name_is_filled(): void
    {
        $this->authenticateWithPermission(
            UserPresetPermission::fromValue(
                UserPresetPermission::EDIT_USER_PRESETS
            )
        );

        $preset = Preset::factory()->create(['user_id' => $this->getUser()->id]);
        $data = Preset::factory()->make(['user_id' => $this->getUser()->id, 'name' => null]);

        $response = $this->patch(
            route('admin.users.presets.update', ['worker' => $this->getUser()->id, 'preset' => $preset->id]),
            $data->toArray()
        );

        $response->assertUnprocessable();
    }

    /**
     * @test
     */
    public function model_id_is_filled(): void
    {
        $this->authenticateWithPermission(
            UserPresetPermission::fromValue(
                UserPresetPermission::EDIT_USER_PRESETS
            )
        );

        $preset = Preset::factory()->create(['user_id' => $this->getUser()->id]);
        $data = Preset::factory()->make(['user_id' => $this->getUser()->id, 'model_id' => null]);

        $response = $this->patch(
            route('admin.users.presets.update', ['worker' => $this->getUser()->id, 'preset' => $preset->id]),
            $data->toArray()
        );

        $response->assertUnprocessable();
    }

    /**
     * @test
     */
    public function name_is_string(): void
    {
        $this->authenticateWithPermission(
            UserPresetPermission::fromValue(
                UserPresetPermission::EDIT_USER_PRESETS
            )
        );

        $preset = Preset::factory()->create(['user_id' => $this->getUser()->id]);
        $data = Preset::factory()->make(['user_id' => $this->getUser()->id, 'name' => null]);
        $data->name = 1;

        $response = $this->patch(
            route('admin.users.presets.update', ['worker' => $this->getUser()->id, 'preset' => $preset->id]),
            $data->toArray()
        );

        $response->assertUnprocessable();
    }

    /**
     * @test
     */
    public function model_id_is_integer(): void
    {
        $this->authenticateWithPermission(
            UserPresetPermission::fromValue(
                UserPresetPermission::EDIT_USER_PRESETS
            )
        );

        $preset = Preset::factory()->create(['user_id' => $this->getUser()->id]);
        $data = Preset::factory()->make(['user_id' => $this->getUser()->id, 'model_id' => null]);
        $data->model_id = 'string';

        $response = $this->patch(
            route('admin.users.presets.update', ['worker' => $this->getUser()->id, 'preset' => $preset->id]),
            $data->toArray()
        );

        $response->assertUnprocessable();
    }
}
