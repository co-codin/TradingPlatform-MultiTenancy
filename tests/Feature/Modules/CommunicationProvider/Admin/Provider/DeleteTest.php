<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\CommunicationProvider\Admin\Provider;

use Modules\CommunicationProvider\Enums\CommunicationProviderPermission;
use Modules\CommunicationProvider\Models\CommunicationProvider;
use Tests\TestCase;

final class DeleteTest extends TestCase
{
    /**
     * @test
     */
    public function can_delete(): void
    {
        $this->authenticateWithPermission(CommunicationProviderPermission::fromValue(CommunicationProviderPermission::DELETE_TELEPHONY_PROVIDER));

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

        $providerId = CommunicationProvider::orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->delete(route('admin.communication.providers.destroy', ['provider' => $providerId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $provider = CommunicationProvider::factory()->create();
        $response = $this->delete(route('admin.communication.providers.destroy', compact('provider')));

        $response->assertUnauthorized();
    }
}
