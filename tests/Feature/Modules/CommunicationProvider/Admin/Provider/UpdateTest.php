<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\CommunicationProvider\Admin\Provider;

use Modules\CommunicationProvider\Enums\CommunicationProviderPermission;
use Modules\CommunicationProvider\Models\CommunicationProvider;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    /**
     * @test
     */
    public function can_update(): void
    {
        $this->authenticateWithPermission(CommunicationProviderPermission::fromValue(CommunicationProviderPermission::EDIT_TELEPHONY_PROVIDER));

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
        $provider = CommunicationProvider::factory()->create();

        $response = $this->patch(route('admin.communication.providers.update', compact('provider')));

        $response->assertUnauthorized();
    }
}
