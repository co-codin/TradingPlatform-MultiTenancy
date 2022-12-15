<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\CommunicationProvider\Admin\Extension;

use Modules\CommunicationProvider\Enums\CommunicationExtensionPermission;
use Modules\CommunicationProvider\Models\CommunicationExtension;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    /**
     * @test
     */
    public function can_update(): void
    {
        $this->authenticateWithPermission(CommunicationExtensionPermission::fromValue(CommunicationExtensionPermission::EDIT_TELEPHONY_EXTENSION));

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
        $extension = CommunicationExtension::factory()->create();

        $response = $this->patch(route('admin.communication.extensions.update', compact('extension')));

        $response->assertUnauthorized();
    }
}
