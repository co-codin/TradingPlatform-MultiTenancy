<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Campaign\Admin;

use Modules\Campaign\Enums\CampaignPermission;
use Modules\Campaign\Models\Campaign;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function can_view_any(): void
    {
        $this->authenticateWithPermission(CampaignPermission::fromValue(CampaignPermission::VIEW_CAMPAIGN));

        $campaign = Campaign::factory()->create();

        $response = $this->getJson(route('admin.campaign.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [$campaign->toArray()],
        ]);
    }

    /**
     * @test
     */
    public function can_not_view_any(): void
    {
        $this->authenticateUser();

        $response = $this->get(route('admin.campaign.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_view(): void
    {
        $this->authenticateWithPermission(
            CampaignPermission::fromValue(CampaignPermission::VIEW_CAMPAIGN)
        );

        $campaign = Campaign::factory()->create();

        $response = $this->get(route('admin.campaign.show', ['campaign' => $campaign]));

        $response->assertOk();
        $response->assertJson(['data' => $campaign->toArray()]);
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $this->authenticateUser();

        $campaign = Campaign::factory()->create();

        $response = $this->get(route('admin.campaign.show', ['campaign' => $campaign]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $campaignId = Campaign::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('admin.campaign.show', ['campaign' => $campaignId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized_view_any(): void
    {
        $response = $this->get(route('admin.campaign.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $campaign = Campaign::factory()->create();

        $response = $this->get(route('admin.campaign.show', ['campaign' => $campaign]));

        $response->assertUnauthorized();
    }
}
