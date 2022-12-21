<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Provider;

use Modules\Communication\Models\CommunicationProvider;
use Modules\User\Enums\UserPermission;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Spatie\Permission\PermissionRegistrar;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class SetDefaultProviderTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function set(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $this->brand->makeCurrent();
        $provider = CommunicationProvider::factory()->create();

        $response = $this->patch(route('admin.users.update', ['worker' => $this->getUser()]), [
            'communication_provider_id' => $provider->id,
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'username',
                'communication_provider_id',
            ],
        ]);
        $response->assertJsonPath('data.communication_provider_id', $provider->id);
    }

    /**
     * @test
     */
    public function set_null(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $this->brand->makeCurrent();

        $response = $this->patch(route('admin.users.update', ['worker' => $this->getUser()]), [
            'communication_provider_id' => null,
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'username',
                'communication_provider_id',
            ],
        ]);
        $response->assertJsonPath('data.communication_provider_id', null);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
}
