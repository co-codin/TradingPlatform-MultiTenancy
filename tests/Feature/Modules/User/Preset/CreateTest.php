<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User\Preset;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\User\Enums\UserPresetPermission;
use Modules\User\Models\Preset;
use Modules\User\Models\User;
use Tests\TestCase;

final class CreateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function authorized_user_can_create(): void
    {
        $this->authenticateWithPermission(
            UserPresetPermission::fromValue(
                UserPresetPermission::CREATE_USER_PRESET_OPTIONS
            )
        );

        $data = Preset::factory()->make(['user_id' => $this->getUser()->id])->toArray();

        $response = $this->post(route('admin.users.presets.store', ['worker' => $this->getUser()->id]), $data);
dd($response->json('message'));
        $response->assertCreated();

        $response->assertJson([
            'data' => $data,
        ]);
    }

    /**
     * @test
     */
    public function unauthorized_user_cant_create(): void
    {
        $user = User::factory()->create();

        $data = Preset::factory()->make(['user_id' => $user->id]);

        $response = $this->post(route('admin.users.presets.store', ['worker' => $user->id]), $data->toArray());

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function name_is_required(): void
    {
        $this->authenticateWithPermission(
            UserPresetPermission::fromValue(
                UserPresetPermission::CREATE_USER_PRESET_OPTIONS
            )
        );

        $data = Preset::factory()->make(['user_id' => $this->getUser()->id]);
        unset($data['name']);

        $response = $this->post(route('admin.users.presets.store', ['worker' => $this->getUser()->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * @test
     */
    public function model_id_is_required(): void
    {
        $this->authenticateWithPermission(
            UserPresetPermission::fromValue(
                UserPresetPermission::CREATE_USER_PRESET_OPTIONS
            )
        );

        $data = Preset::factory()->make(['user_id' => $this->getUser()->id]);
        unset($data['model_id']);

        $response = $this->post(route('admin.users.presets.store', ['worker' => $this->getUser()->id]), $data->toArray());

        $response->assertUnprocessable();
    }
}
