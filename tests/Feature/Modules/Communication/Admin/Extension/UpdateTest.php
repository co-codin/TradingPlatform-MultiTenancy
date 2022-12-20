<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Extension;

use Modules\Communication\Enums\CommunicationExtensionPermission;
use Modules\Communication\Models\CommunicationExtension;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Spatie\Permission\PermissionRegistrar;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_update(): void
    {
        $this->authenticateWithPermission(CommunicationExtensionPermission::fromValue(CommunicationExtensionPermission::EDIT_COMMUNICATION_EXTENSION));

        $this->brand->makeCurrent();
        $extension = CommunicationExtension::factory()->create();
        $data = CommunicationExtension::factory()->make();

        $response = $this->patch(route('admin.communication.extensions.update', compact('extension')), $data->toArray());

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'user_id',
                'provider_id',
            ],
        ]);
        $response->assertJson(['data' => $data->toArray()]);
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $extension = CommunicationExtension::factory()->create();
        $data = CommunicationExtension::factory()->make();

        $response = $this->patch(route('admin.communication.extensions.update', compact('extension')), $data->toArray());

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $extensionId = CommunicationExtension::orderByDesc('id')->first()?->id + 1 ?? 1;

        $data = CommunicationExtension::factory()->make();

        $response = $this->patch(route('admin.communication.extensions.update', ['extension' => $extensionId]),
            $data->toArray());

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();
        $extension = CommunicationExtension::factory()->create();

        $response = $this->patch(route('admin.communication.extensions.update', compact('extension')));

        $response->assertUnauthorized();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
}
