<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Campaign\Admin;

use Modules\Campaign\Enums\CampaignPermission;
use Modules\Campaign\Models\Campaign;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdateTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function can_update(): void
    {
        $this->authenticateWithPermission(
            CampaignPermission::fromValue(CampaignPermission::EDIT_CAMPAIGN)
        );

        $campaign = Campaign::factory()->create();

        $campaignData = Campaign::factory()->make()->toArray();

        $response = $this->patchJson(route('admin.campaign.update', ['campaign' => $campaign->id]), $campaignData);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => array_keys($campaignData),
        ]);
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $campaign = Campaign::factory()->create();

        $data = Campaign::factory()->make();

        $response = $this->patch(
            route('admin.campaign.update', ['campaign' => $campaign]),
            $data->toArray()
        );

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $campaignId = Campaign::orderByDesc('id')->first()?->id + 1 ?? 1;
        $data = Campaign::factory()->make();

        $response = $this->patch(
            route('admin.campaign.update', ['campaign' => $campaignId]),
            $data->toArray()
        );

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $campaign = Campaign::factory()->create();

        $response = $this->patch(route('admin.campaign.update', ['campaign' => $campaign]));

        $response->assertUnauthorized();
    }
}
