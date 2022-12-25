<?php

declare(strict_types=1);

namespace Modules\Communication\Admin\Extension;

use Modules\Communication\Enums\CommunicationExtensionPermission;
use Modules\Communication\Models\CommunicationExtension;
use Modules\User\Models\User;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Spatie\Permission\PermissionRegistrar;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class BulkReplaceByUserTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_create(): void
    {
        $this->authenticateUser();
        $this->addPermissions([
            CommunicationExtensionPermission::fromValue(CommunicationExtensionPermission::CREATE_COMMUNICATION_EXTENSION),
            CommunicationExtensionPermission::fromValue(CommunicationExtensionPermission::DELETE_COMMUNICATION_EXTENSION),
        ]);

        $user = User::factory()->create();

        $this->brand->makeCurrent();
        $oldExtensions = [
            CommunicationExtension::factory()->create(['user_id' => $user->id]),
            CommunicationExtension::factory()->create(['user_id' => $user->id]),
        ];
        $data = [
            'user_id' => $user->id,
            'extensions' => [
                CommunicationExtension::factory()->make()->toArray(),
                CommunicationExtension::factory()->make()->toArray(),
            ],
        ];

        $response = $this->put(route('admin.communication.extensions.bulk-replace-by-worker'), $data);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'provider_id',
                    'user_id',
                ],
            ],
        ]);
        $response->assertJsonCount(2, 'data');
        array_walk($oldExtensions, $this->assertModelMissing(...));
    }

    /**
     * @test
     */
    public function can_only_create(): void
    {
        $this->authenticateWithPermission(CommunicationExtensionPermission::fromValue(CommunicationExtensionPermission::CREATE_COMMUNICATION_EXTENSION));

        $user = User::factory()->create();

        $this->brand->makeCurrent();
        CommunicationExtension::factory(2)->create(['user_id' => $user->id]);
        $data = [
            'user_id' => $user->id,
            'extensions' => [
                CommunicationExtension::factory()->make()->toArray(),
            ],
        ];

        $response = $this->put(route('admin.communication.extensions.bulk-replace-by-worker'), $data);
        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_not_create(): void
    {
        $this->authenticateUser();

        $user = User::factory()->create();

        $this->brand->makeCurrent();
        $data = [
            'user_id' => $user->id,
            'extensions' => [
                CommunicationExtension::factory()->make()->toArray(),
            ],
        ];
        $response = $this->put(route('admin.communication.extensions.bulk-replace-by-worker'), $data);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->put(route('admin.communication.extensions.bulk-replace-by-worker'));

        $response->assertUnauthorized();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
}
