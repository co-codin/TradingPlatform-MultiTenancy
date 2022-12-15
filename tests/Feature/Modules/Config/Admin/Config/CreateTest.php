<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Config\Admin\Config;

use Modules\Config\Enums\ConfigPermission;
use Modules\Config\Models\Config;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can create config.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_a_user_can_create_config(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::CREATE_CONFIGS));

        $this->brand->makeCurrent();

        $data = Config::factory()->make();

        $response = $this->postJson(route('admin.configs.store'), $data->toArray());

        $response->assertCreated();

        $response->assertJson([
            'data' => $data->toArray(),
        ]);
    }

    /**
     * Test unauthorized user can`t create config.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized_user_cant_create_config(): void
    {
        $this->brand->makeCurrent();

        $data = Config::factory()->make();

        $response = $this->postJson(route('admin.configs.store'), $data->toArray());

        $response->assertUnauthorized();
    }

    /**
     * Test config type id name required.
     *
     * @return void
     *
     * @test
     */
    final public function config_type_id_is_required(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::CREATE_CONFIGS));

        $this->brand->makeCurrent();

        $data = Config::factory()->make()->toArray();
        unset($data['config_type_id']);

        $response = $this->postJson(route('admin.configs.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test config data type required.
     *
     * @return void
     *
     * @test
     */
    final public function config_data_type_is_required(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::CREATE_CONFIGS));

        $this->brand->makeCurrent();

        $data = Config::factory()->make()->toArray();
        unset($data['data_type']);

        $response = $this->postJson(route('admin.configs.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test config name required.
     *
     * @return void
     *
     * @test
     */
    final public function config_name_is_required(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::CREATE_CONFIGS));

        $this->brand->makeCurrent();

        $data = Config::factory()->make()->toArray();
        unset($data['name']);

        $response = $this->postJson(route('admin.configs.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test config value required.
     *
     * @return void
     *
     * @test
     */
    final public function config_value_is_required(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::CREATE_CONFIGS));

        $this->brand->makeCurrent();

        $data = Config::factory()->make()->toArray();
        unset($data['value']);

        $response = $this->postJson(route('admin.configs.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test config type id is integer.
     *
     * @return void
     *
     * @test
     */
    final public function config_type_id_is_integer(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::CREATE_CONFIGS));

        $this->brand->makeCurrent();

        $data = Config::factory()->make();
        $data->config_type_id = 'Hello world!';

        $response = $this->postJson(route('admin.configs.store'), $data->toArray());

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
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::CREATE_CONFIGS));

        $this->brand->makeCurrent();

        $data = Config::factory()->make();
        $data->data_type = 1;

        $response = $this->postJson(route('admin.configs.store'), $data->toArray());

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
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::CREATE_CONFIGS));

        $this->brand->makeCurrent();

        $data = Config::factory()->make();
        $data->name = 1;

        $response = $this->postJson(route('admin.configs.store'), $data->toArray());

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
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::CREATE_CONFIGS));

        $this->brand->makeCurrent();

        $data = Config::factory()->make();
        $data->value = 1;

        $response = $this->postJson(route('admin.configs.store'), $data->toArray());

        $response->assertUnprocessable();
    }
}
