<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Config\Admin\ConfigType;

use Modules\Config\Enums\ConfigTypePermission;
use Modules\Config\Models\ConfigType;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can get config types all list.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_get_config_types_all_list(): void
    {
        $this->authenticateWithPermission(ConfigTypePermission::fromValue(ConfigTypePermission::VIEW_CONFIG_TYPES));

        $this->brand->makeCurrent();

        $configType = ConfigType::factory()->create();

        $response = $this->getJson(route('admin.configs.types.all'));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                $configType->toArray(),
            ],
        ]);
    }

    /**
     * Test authorized user can get config types list.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_get_config_types_list(): void
    {
        $this->authenticateWithPermission(ConfigTypePermission::fromValue(ConfigTypePermission::VIEW_CONFIG_TYPES));

        $this->brand->makeCurrent();

        $configType = ConfigType::factory()->create();

        $response = $this->getJson(route('admin.configs.types.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                $configType->toArray(),
            ],
        ]);
    }

    /**
     * Test unauthorized user cant get config types list.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized_user_cant_get_config_types_list(): void
    {
        $this->brand->makeCurrent();

        ConfigType::factory()->create();

        $response = $this->getJson(route('admin.configs.types.index'));

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user can get config type.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_get_config_type(): void
    {
        $this->authenticateWithPermission(ConfigTypePermission::fromValue(ConfigTypePermission::VIEW_CONFIG_TYPES));

        $this->brand->makeCurrent();

        $configType = ConfigType::factory()->create();

        $response = $this->getJson(route('admin.configs.types.show', ['type' => $configType->id]));

        $response->assertOk();

        $response->assertJson([
            'data' => $configType->toArray(),
        ]);
    }

    /**
     * Test unauthorized user cant get config types list.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized_user_cant_get_config_types(): void
    {
        $this->brand->makeCurrent();

        $configType = ConfigType::factory()->create();

        $response = $this->getJson(route('admin.configs.types.show', ['type' => $configType->id]));

        $response->assertUnauthorized();
    }
}
