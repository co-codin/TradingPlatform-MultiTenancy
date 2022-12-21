<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Extension;

use Modules\Communication\Enums\CommunicationExtensionPermission;
use Modules\Communication\Models\CommunicationExtension;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Spatie\Permission\PermissionRegistrar;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class DeleteTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_delete(): void
    {
        $this->authenticateWithPermission(CommunicationExtensionPermission::fromValue(CommunicationExtensionPermission::DELETE_COMMUNICATION_EXTENSION));

        $this->brand->makeCurrent();
        $extension = CommunicationExtension::factory()->create();
        $response = $this->delete(route('admin.communication.extensions.destroy', compact('extension')));

        $response->assertNoContent();
        $this->assertModelMissing($extension);
    }

    /**
     * @test
     */
    public function cant_delete(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $extension = CommunicationExtension::factory()->create();
        $response = $this->delete(route('admin.communication.extensions.destroy', compact('extension')));

        $response->assertForbidden();
        $this->assertModelExists($extension);
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $extensionId = CommunicationExtension::orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->delete(route('admin.communication.extensions.destroy', ['extension' => $extensionId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();
        $extension = CommunicationExtension::factory()->create();
        $response = $this->delete(route('admin.communication.extensions.destroy', compact('extension')));

        $response->assertUnauthorized();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
}
