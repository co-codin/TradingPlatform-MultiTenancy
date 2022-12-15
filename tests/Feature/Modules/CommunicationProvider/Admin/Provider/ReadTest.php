<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\CommunicationProvider\Admin\Provider;

use Modules\CommunicationProvider\Enums\CommunicationProviderPermission;
use Modules\CommunicationProvider\Models\CommunicationProvider;
use Tests\TestCase;

final class ReadTest extends TestCase
{
    /**
     * @test
     */
    public function can_view_any(): void
    {
        CommunicationProvider::factory()->count(10)->create();

        $this->authenticateWithPermission(CommunicationProviderPermission::fromValue(CommunicationProviderPermission::VIEW_TELEPHONY_PROVIDER));

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
        CommunicationProvider::factory()->count(10)->create();

        $this->authenticateWithPermission(CommunicationProviderPermission::fromValue(CommunicationProviderPermission::VIEW_TELEPHONY_PROVIDER));

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

        $response = $this->get(route('admin.communication.providers.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_not_view_all(): void
    {
        $this->authenticateUser();

        $response = $this->get(route('admin.communication.providers.all'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_view(): void
    {
        $this->authenticateWithPermission(CommunicationProviderPermission::fromValue(CommunicationProviderPermission::VIEW_TELEPHONY_PROVIDER));

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
    public function not_unauthorized_view(): void
    {
        $provider = CommunicationProvider::factory()->create();

        $response = $this->get(route('admin.communication.providers.show', compact('provider')));

        $response->assertUnauthorized();
    }
}
