<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Extension;

use Modules\Communication\Enums\CommunicationExtensionPermission;
use Modules\Communication\Models\CommunicationExtension;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Spatie\Permission\PermissionRegistrar;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_create(): void
    {
        $this->authenticateWithPermission(CommunicationExtensionPermission::fromValue(CommunicationExtensionPermission::CREATE_COMMUNICATION_EXTENSION));

        $this->brand->makeCurrent();
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

        $this->brand->makeCurrent();
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
