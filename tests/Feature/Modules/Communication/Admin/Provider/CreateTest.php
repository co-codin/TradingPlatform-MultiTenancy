<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Provider;

use Modules\Communication\Enums\CommunicationProviderPermission;
use Modules\Communication\Models\CommunicationProvider;
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
        $this->authenticateWithPermission(CommunicationProviderPermission::fromValue(CommunicationProviderPermission::CREATE_COMMUNICATION_PROVIDER));

        $this->brand->makeCurrent();
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
    public function cant_create(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $response = $this->post(route('admin.communication.providers.store'),
            CommunicationProvider::factory()->make()->toArray());

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function unauthorized(): void
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
