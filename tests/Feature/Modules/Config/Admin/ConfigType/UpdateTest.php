<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Config\Admin\ConfigType;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Config\Enums\ConfigTypePermission;
use Modules\Config\Models\ConfigType;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can update config type.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_update_config_type(): void
    {
        $this->authenticateWithPermission(ConfigTypePermission::fromValue(ConfigTypePermission::EDIT_CONFIG_TYPES));

        $configType = ConfigType::factory()->create();
        $data = ConfigType::factory()->make();

        $response = $this->patchJson(route('admin.configs.types.update', ['type' => $configType->id]), $data->toArray());

        $response->assertOk();

        $response->assertJson([
            'data' => $data->toArray(),
        ]);
    }

    /**
     * Test unauthorized user can`t update config type.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized_user_cant_update_config_type(): void
    {
        $configType = ConfigType::factory()->create();
        $data = ConfigType::factory()->make();

        $response = $this->patchJson(route('admin.configs.types.update', ['type' => $configType->id]), $data->toArray());

        $response->assertUnauthorized();
    }

    /**
     * Test config type name filled.
     *
     * @return void
     *
     * @test
     */
    final public function config_type_name_is_filled(): void
    {
        $this->authenticateWithPermission(ConfigTypePermission::fromValue(ConfigTypePermission::EDIT_CONFIG_TYPES));

        $configType = ConfigType::factory()->create();
        $data = ConfigType::factory()->make(['name' => null])->toArray();

        $response = $this->patchJson(route('admin.configs.types.update', ['type' => $configType->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test config type name is string.
     *
     * @return void
     *
     * @test
     */
    final public function config_type_name_is_string(): void
    {
        $this->authenticateWithPermission(ConfigTypePermission::fromValue(ConfigTypePermission::EDIT_CONFIG_TYPES));

        $configType = ConfigType::factory()->create();
        $data = ConfigType::factory()->make();
        $data->name = 1;

        $response = $this->patchJson(route('admin.configs.types.update', ['type' => $configType->id]), $data->toArray());

        $response->assertUnprocessable();
    }
}
