<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Provider;

use Modules\Communication\Enums\CommunicationProviderPermission;
use Modules\Communication\Models\CommunicationProvider;
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
        $this->authenticateWithPermission(CommunicationProviderPermission::fromValue(CommunicationProviderPermission::DELETE_COMMUNICATION_PROVIDER));

        $this->brand->makeCurrent();
        $provider = CommunicationProvider::factory()->create();
        $response = $this->delete(route('admin.communication.providers.destroy', compact('provider')));

        $response->assertNoContent();
        $this->assertModelMissing($provider);
    }

    /**
     * @test
     */
    public function cant_delete(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $provider = CommunicationProvider::factory()->create();
        $response = $this->delete(route('admin.communication.providers.destroy', compact('provider')));

        $response->assertForbidden();
        $this->assertModelExists($provider);
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $providerId = CommunicationProvider::orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->delete(route('admin.communication.providers.destroy', ['provider' => $providerId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();
        $provider = CommunicationProvider::factory()->create();
        $response = $this->delete(route('admin.communication.providers.destroy', compact('provider')));

        $response->assertUnauthorized();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
}
