<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\CommunicationProvider\Admin\Provider;

use Modules\CommunicationProvider\Enums\CommunicationProviderPermission;
use Modules\CommunicationProvider\Models\CommunicationProvider;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

final class CreateTest extends TestCase
{
    /**
     * @test
     */
    public function can_create(): void
    {
        $this->authenticateWithPermission(CommunicationProviderPermission::fromValue(CommunicationProviderPermission::CREATE_TELEPHONY_PROVIDER));

        $data = CommunicationProvider::factory()->make();

        $response = $this->post(route('admin.communication.providers.store'), $data->toArray());

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ],
        ]);
        $response->assertJson(['data' => $data->toArray()]);
    }

    /**
     * @test
     */
    public function can_not_create(): void
    {
        $this->authenticateUser();

        $response = $this->post(route('admin.communication.providers.store'),
            CommunicationProvider::factory()->make()->toArray());

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('admin.communication.providers.store'));

        $response->assertUnauthorized();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
}
