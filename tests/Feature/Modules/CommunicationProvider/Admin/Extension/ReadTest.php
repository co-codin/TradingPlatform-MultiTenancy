<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\CommunicationProvider\Admin\Extension;

use Modules\CommunicationProvider\Enums\CommunicationExtensionPermission;
use Modules\CommunicationProvider\Models\CommunicationExtension;
use Tests\TestCase;

final class ReadTest extends TestCase
{
    /**
     * @test
     */
    public function can_view_any(): void
    {
        CommunicationExtension::factory()->count(10)->create();

        $this->authenticateWithPermission(CommunicationExtensionPermission::fromValue(CommunicationExtensionPermission::VIEW_TELEPHONY_EXTENSION));

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

        $response = $this->get(route('admin.communication.extensions.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_view(): void
    {
        $this->authenticateWithPermission(CommunicationExtensionPermission::fromValue(CommunicationExtensionPermission::VIEW_TELEPHONY_EXTENSION));

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
        $extension = CommunicationExtension::factory()->create();

        $response = $this->get(route('admin.communication.extensions.show', compact('extension')));

        $response->assertUnauthorized();
    }
}
