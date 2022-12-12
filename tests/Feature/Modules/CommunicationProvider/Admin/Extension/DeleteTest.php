<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\CommunicationProvider\Admin\Extension;

use Modules\CommunicationProvider\Enums\CommunicationExtensionPermission;
use Modules\CommunicationProvider\Models\CommunicationExtension;
use Tests\TestCase;

final class DeleteTest extends TestCase
{
    /**
     * @test
     */
    public function can_delete(): void
    {
        $this->authenticateWithPermission(CommunicationExtensionPermission::fromValue(CommunicationExtensionPermission::DELETE_TELEPHONY_EXTENSION));

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

        $extensionId = CommunicationExtension::orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->delete(route('admin.communication.extensions.destroy', ['extension' => $extensionId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $extension = CommunicationExtension::factory()->create();
        $response = $this->delete(route('admin.communication.extensions.destroy', compact('extension')));

        $response->assertUnauthorized();
    }
}
