<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Config\Admin\Config;

use Modules\Config\Enums\ConfigPermission;
use Modules\Config\Models\Config;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can update config.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_update_config(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::EDIT_CONFIGS));

        $this->brand->makeCurrent();

        $config = Config::factory()->create();
        $data = Config::factory()->make();

        $response = $this->patchJson(route('admin.configs.update', ['config' => $config->id]), $data->toArray());

        $response->assertOk();

        $response->assertJson([
            'data' => $data->toArray(),
        ]);
    }

    /**
     * Test unauthorized user can`t update config.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized_user_cant_update_config(): void
    {
        $this->brand->makeCurrent();

        $config = Config::factory()->create();
        $data = Config::factory()->make();

        $response = $this->patchJson(route('admin.configs.update', ['config' => $config->id]), $data->toArray());

        $response->assertUnauthorized();
    }

    /**
     * Test config type id filled.
     *
     * @return void
     *
     * @test
     */
    final public function config_type_id_is_filled(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::EDIT_CONFIGS));

        $this->brand->makeCurrent();

        $config = Config::factory()->create();
        $data = Config::factory()->make(['config_type_id' => null])->toArray();

        $response = $this->patchJson(route('admin.configs.update', ['config' => $config->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test config data type filled.
     *
     * @return void
     *
     * @test
     */
    final public function config_data_type_is_filled(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::EDIT_CONFIGS));

        $this->brand->makeCurrent();

        $config = Config::factory()->create();
        $data = Config::factory()->make(['data_type' => null])->toArray();

        $response = $this->patchJson(route('admin.configs.update', ['config' => $config->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test config name filled.
     *
     * @return void
     *
     * @test
     */
    final public function config_name_is_filled(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::EDIT_CONFIGS));

        $this->brand->makeCurrent();

        $config = Config::factory()->create();
        $data = Config::factory()->make(['name' => null])->toArray();

        $response = $this->patchJson(route('admin.configs.update', ['config' => $config->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test config name filled.
     *
     * @return void
     *
     * @test
     */
    final public function config_value_is_filled(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::EDIT_CONFIGS));

        $this->brand->makeCurrent();

        $config = Config::factory()->create();
        $data = Config::factory()->make(['value' => null])->toArray();

        $response = $this->patchJson(route('admin.configs.update', ['config' => $config->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test config name is string.
     *
     * @return void
     *
     * @test
     */
    final public function config_type_id_is_string(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::EDIT_CONFIGS));

        $this->brand->makeCurrent();

        $config = Config::factory()->create();
        $data = Config::factory()->make();
        $data->config_type_id = 'a';

        $response = $this->patchJson(route('admin.configs.update', ['config' => $config->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test config data_type is string.
     *
     * @return void
     *
     * @test
     */
    final public function config_data_type_is_string(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::EDIT_CONFIGS));

        $this->brand->makeCurrent();

        $config = Config::factory()->create();
        $data = Config::factory()->make();
        $data->data_type = 1;

        $response = $this->patchJson(route('admin.configs.update', ['config' => $config->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test config name is string.
     *
     * @return void
     *
     * @test
     */
    final public function config_name_is_string(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::EDIT_CONFIGS));

        $this->brand->makeCurrent();

        $config = Config::factory()->create();
        $data = Config::factory()->make();
        $data->name = 1;

        $response = $this->patchJson(route('admin.configs.update', ['config' => $config->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test config value is string.
     *
     * @return void
     *
     * @test
     */
    final public function config_value_is_string(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::EDIT_CONFIGS));

        $this->brand->makeCurrent();

        $config = Config::factory()->create();
        $data = Config::factory()->make();
        $data->value = 1;

        $response = $this->patchJson(route('admin.configs.update', ['config' => $config->id]), $data->toArray());

        $response->assertUnprocessable();
    }
}
