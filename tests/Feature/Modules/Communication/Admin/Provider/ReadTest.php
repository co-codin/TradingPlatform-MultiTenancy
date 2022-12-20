<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Provider;

use Modules\Communication\Enums\CommunicationProviderPermission;
use Modules\Communication\Models\CommunicationProvider;
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
        $this->authenticateWithPermission(CommunicationProviderPermission::fromValue(CommunicationProviderPermission::VIEW_COMMUNICATION_PROVIDER));

        $this->brand->makeCurrent();
        CommunicationProvider::factory()->count(10)->create();

        $response = $this->get(route('admin.communication.providers.index'));

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function can_view_all(): void
    {
        $this->authenticateWithPermission(CommunicationProviderPermission::fromValue(CommunicationProviderPermission::VIEW_COMMUNICATION_PROVIDER));

        $this->brand->makeCurrent();
        CommunicationProvider::factory()->count(10)->create();

        $response = $this->get(route('admin.communication.providers.all'));

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
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
        $response = $this->get(route('admin.communication.providers.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_not_view_all(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $response = $this->get(route('admin.communication.providers.all'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_view(): void
    {
        $this->authenticateWithPermission(CommunicationProviderPermission::fromValue(CommunicationProviderPermission::VIEW_COMMUNICATION_PROVIDER));

        $this->brand->makeCurrent();
        $provider = CommunicationProvider::factory()->create();

        $response = $this->get(route('admin.communication.providers.show', compact('provider')));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ],
        ]);
        $response->assertJson(['data' => $provider->toArray()]);
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $provider = CommunicationProvider::factory()->create();

        $response = $this->get(route('admin.communication.providers.show', compact('provider')));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $providerId = CommunicationProvider::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('admin.communication.providers.show', ['provider' => $providerId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized_view_any(): void
    {
        $response = $this->get(route('admin.communication.providers.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function unauthorized_view_all(): void
    {
        $response = $this->get(route('admin.communication.providers.all'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function unauthorized_view(): void
    {
        $this->brand->makeCurrent();
        $provider = CommunicationProvider::factory()->create();

        $response = $this->get(route('admin.communication.providers.show', compact('provider')));

        $response->assertUnauthorized();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
}
