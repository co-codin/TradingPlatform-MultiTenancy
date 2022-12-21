<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Provider;

use Modules\Communication\Enums\CommunicationProviderPermission;
use Modules\Communication\Models\CommunicationProvider;
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
        $this->authenticateWithPermission(CommunicationProviderPermission::fromValue(CommunicationProviderPermission::EDIT_COMMUNICATION_PROVIDER));

        $this->brand->makeCurrent();
        $provider = CommunicationProvider::factory()->create();
        $data = CommunicationProvider::factory()->make();

        $response = $this->patch(route('admin.communication.providers.update', compact('provider')), $data->toArray());

        $response->assertOk();
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
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $provider = CommunicationProvider::factory()->create();
        $data = CommunicationProvider::factory()->make();

        $response = $this->patch(route('admin.communication.providers.update', compact('provider')), $data->toArray());

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $providerId = CommunicationProvider::orderByDesc('id')->first()?->id + 1 ?? 1;

        $data = CommunicationProvider::factory()->make();

        $response = $this->patch(route('admin.communication.providers.update', ['provider' => $providerId]),
            $data->toArray());

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();
        $provider = CommunicationProvider::factory()->create();

        $response = $this->patch(route('admin.communication.providers.update', compact('provider')));

        $response->assertUnauthorized();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
}
