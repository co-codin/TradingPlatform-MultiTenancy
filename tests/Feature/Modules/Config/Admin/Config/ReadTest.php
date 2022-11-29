<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Config\Admin\Config;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Config\Enums\ConfigPermission;
use Modules\Config\Models\Config;
use Tests\TestCase;

final class ReadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can get configs list.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_get_configs_list(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::VIEW_CONFIGS));

        $config = Config::factory()->create();

        $response = $this->getJson(route('admin.configs.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                $config->toArray(),
            ],
        ]);
    }

    /**
     * Test unauthorized user cant get configs list.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized_user_cant_get_configs_list(): void
    {
        Config::factory()->create();

        $response = $this->getJson(route('admin.configs.index'));

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user can get configs list.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_get_config(): void
    {
        $this->authenticateWithPermission(ConfigPermission::fromValue(ConfigPermission::VIEW_CONFIGS));

        $config = Config::factory()->create();

        $response = $this->getJson(route('admin.configs.show', ['config' => $config->id]));

        $response->assertOk();

        $response->assertJson([
            'data' => $config->toArray(),
        ]);
    }

    /**
     * Test unauthorized user cant get configs list.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized_user_cant_get_config(): void
    {
        $config = Config::factory()->create();

        $response = $this->getJson(route('admin.configs.show', ['config' => $config->id]));

        $response->assertUnauthorized();
    }
}
