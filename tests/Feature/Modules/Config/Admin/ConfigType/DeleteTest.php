<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Config\Admin\ConfigType;

use Modules\Config\Enums\ConfigTypePermission;
use Modules\Config\Models\ConfigType;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class DeleteTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can delete config type.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_delete_config_type(): void
    {
        $this->authenticateWithPermission(ConfigTypePermission::fromValue(ConfigTypePermission::DELETE_CONFIG_TYPES));

        $this->brand->makeCurrent();

        $configType = ConfigType::factory()->create();

        $response = $this->deleteJson(route('admin.configs.types.destroy', ['type' => $configType->id]));

        $response->assertNoContent();
    }

    /**
     * Test unauthorized user can`t delete config type.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized_user_cant_delete_config_type(): void
    {
        $this->brand->makeCurrent();

        $configType = ConfigType::factory()->create();

        $response = $this->patchJson(route('admin.configs.types.destroy', ['type' => $configType->id]));

        $response->assertUnauthorized();
    }
}
