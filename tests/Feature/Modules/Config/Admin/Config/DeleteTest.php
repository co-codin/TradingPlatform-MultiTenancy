<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Config\Admin\Config;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Config\Enums\ConfigPermission;
use Modules\Config\Models\Config;
use Tests\TestCase;

final class DeleteTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can delete config.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_delete_config(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::DELETE_CONFIGS));

        $config = Config::factory()->create();

        $response = $this->deleteJson(route('admin.configs.destroy', ['config' => $config->id]));

        $response->assertNoContent();
    }

    /**
     * Test unauthorized user can`t delete config.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized_user_cant_delete_config(): void
    {
        $config = Config::factory()->create();

        $response = $this->patchJson(route('admin.configs.destroy', ['config' => $config->id]));

        $response->assertUnauthorized();
    }
}
