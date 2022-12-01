<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Config\Admin\ConfigType;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Config\Enums\ConfigTypePermission;
use Modules\Config\Models\ConfigType;
use Tests\TestCase;

final class DeleteTest extends TestCase
{
    use DatabaseTransactions;

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
        $configType = ConfigType::factory()->create();

        $response = $this->patchJson(route('admin.configs.types.destroy', ['type' => $configType->id]));

        $response->assertUnauthorized();
    }
}
