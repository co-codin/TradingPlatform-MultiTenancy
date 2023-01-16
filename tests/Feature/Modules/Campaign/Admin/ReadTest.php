<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Campaign\Admin;

use Modules\Campaign\Enums\CampaignPermission;
use Modules\Campaign\Models\Campaign;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_view_any(): void
    {
        $this->authenticateWithPermission(CampaignPermission::fromValue(CampaignPermission::VIEW_CAMPAIGN));

        $this->brand->makeCurrent();

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

        $this->brand->makeCurrent();

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

        $this->brand->makeCurrent();

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

        $this->brand->makeCurrent();

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

        $this->brand->makeCurrent();

        $campaignId = Campaign::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('admin.campaign.show', ['campaign' => $campaignId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized_view_any(): void
    {
        $this->brand->makeCurrent();

        $response = $this->get(route('admin.campaign.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $this->brand->makeCurrent();

        $campaign = Campaign::factory()->create();

        $response = $this->get(route('admin.campaign.show', ['campaign' => $campaign]));

        $response->assertUnauthorized();
    }
}
