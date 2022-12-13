<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\CommunicationProvider\Admin\Extension;

use Modules\CommunicationProvider\Enums\CommunicationExtensionPermission;
use Modules\CommunicationProvider\Models\CommunicationExtension;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

final class CreateTest extends TestCase
{
    /**
     * @test
     */
    public function can_create(): void
    {
        $this->authenticateWithPermission(CommunicationExtensionPermission::fromValue(CommunicationExtensionPermission::CREATE_TELEPHONY_EXTENSION));

        $data = CommunicationExtension::factory()->make();

        $response = $this->post(route('admin.communication.extensions.store'), $data->toArray());

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'provider_id',
                'user_id',
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

        $response = $this->post(route('admin.communication.extensions.store'),
            CommunicationExtension::factory()->make()->toArray());

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('admin.communication.extensions.store'));

        $response->assertUnauthorized();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
}
