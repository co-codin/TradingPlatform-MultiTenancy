<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Config\Admin\ConfigType;

use Modules\Config\Enums\ConfigTypePermission;
use Modules\Config\Models\ConfigType;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can create config type.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_create_config_type(): void
    {
        $this->authenticateWithPermission(ConfigTypePermission::fromValue(ConfigTypePermission::CREATE_CONFIG_TYPES));

        $this->brand->makeCurrent();

        $data = ConfigType::factory()->make();

        $response = $this->postJson(route('admin.configs.types.store'), $data->toArray());

        $response->assertCreated();

        $response->assertJson([
            'data' => $data->toArray(),
        ]);
    }

    /**
     * Test unauthorized user can`t create config type.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized_user_cant_create_config_type(): void
    {
        $this->brand->makeCurrent();

        $data = ConfigType::factory()->make();

        $response = $this->postJson(route('admin.configs.types.store'), $data->toArray());

        $response->assertUnauthorized();
    }

    /**
     * Test config type name required.
     *
     * @return void
     *
     * @test
     */
    final public function config_type_name_is_required(): void
    {
        $this->authenticateWithPermission(ConfigTypePermission::fromValue(ConfigTypePermission::CREATE_CONFIG_TYPES));

        $this->brand->makeCurrent();

        $data = ConfigType::factory()->make()->toArray();
        unset($data['name']);

        $response = $this->postJson(route('admin.configs.types.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test config type name is string.
     *
     * @return void
     *
     * @test
     */
    final public function config_name_is_string(): void
    {
        $this->authenticateWithPermission(ConfigTypePermission::fromValue(ConfigTypePermission::CREATE_CONFIG_TYPES));

        $this->brand->makeCurrent();

        $data = ConfigType::factory()->make();
        $data->name = 1;

        $response = $this->postJson(route('admin.configs.types.store'), $data->toArray());

        $response->assertUnprocessable();
    }
}
