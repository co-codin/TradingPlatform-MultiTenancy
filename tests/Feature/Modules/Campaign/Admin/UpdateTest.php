<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Campaign\Admin;

use Modules\Campaign\Enums\CampaignPermission;
use Modules\Campaign\Models\Campaign;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_update(): void
    {
        $this->authenticateWithPermission(
            CampaignPermission::fromValue(CampaignPermission::EDIT_CAMPAIGN)
        );

        $this->brand->makeCurrent();

        $campaign = Campaign::factory()->create();
        $campaignData = Campaign::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $response = $this->patch(route('admin.campaign.update', ['campaign' => $campaign]), $campaignData);

        $response->assertOk();
        $response->assertJson(['data' => $campaignData]);
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $campaign = Campaign::factory()->create();
        $data = Campaign::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(
            route('admin.campaign.update', ['campaign' => $campaign]), $data->toArray()
        );

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $campaignId = Campaign::orderByDesc('id')->first()?->id + 1 ?? 1;
        $data = Campaign::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(route('admin.campaign.update', ['campaign' => $campaignId]),
            $data->toArray());

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $campaign = Campaign::factory()->create();

        $response = $this->patch(route('admin.campaign.update', ['campaign' => $campaign]));

        $response->assertUnauthorized();
    }
}
