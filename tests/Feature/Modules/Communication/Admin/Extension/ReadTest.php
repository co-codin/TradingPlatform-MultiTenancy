<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Extension;

use Modules\Communication\Enums\CommunicationExtensionPermission;
use Modules\Communication\Models\CommunicationExtension;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Spatie\Permission\PermissionRegistrar;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_view_any(): void
    {
        $this->authenticateWithPermission(CommunicationExtensionPermission::fromValue(CommunicationExtensionPermission::VIEW_COMMUNICATION_EXTENSION));

        $this->brand->makeCurrent();
        CommunicationExtension::factory()->count(10)->create();

        $response = $this->get(route('admin.communication.extensions.index'));

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'user_id',
                    'provider_id',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function can_not_view_any(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $response = $this->get(route('admin.communication.extensions.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_view(): void
    {
        $this->authenticateWithPermission(CommunicationExtensionPermission::fromValue(CommunicationExtensionPermission::VIEW_COMMUNICATION_EXTENSION));

        $this->brand->makeCurrent();
        $extension = CommunicationExtension::factory()->create();

        $response = $this->get(route('admin.communication.extensions.show', compact('extension')));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'user_id',
                'provider_id',
            ],
        ]);
        $response->assertJson(['data' => $extension->toArray()]);
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $extension = CommunicationExtension::factory()->create();

        $response = $this->get(route('admin.communication.extensions.show', compact('extension')));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $extensionId = CommunicationExtension::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('admin.communication.extensions.show', ['extension' => $extensionId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized_view_any(): void
    {
        $response = $this->get(route('admin.communication.extensions.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $this->brand->makeCurrent();
        $extension = CommunicationExtension::factory()->create();

        $response = $this->get(route('admin.communication.extensions.show', compact('extension')));

        $response->assertUnauthorized();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
}
