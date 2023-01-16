<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Campaign\Admin;

use Modules\Campaign\Enums\CampaignPermission;
use Modules\Campaign\Models\Campaign;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ChangeStatusTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_change_status(): void
    {
        $this->authenticateWithPermission(
            CampaignPermission::fromValue(CampaignPermission::EDIT_CAMPAIGN)
        );

        $this->brand->makeCurrent();

        $campaign = Campaign::factory()->create();

        $response = $this->patch(route('admin.campaign.change-status', ['campaign' => $campaign]), []);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function can_not_change_status(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $campaign = Campaign::factory()->create();

        $response = $this->patch(
            route('admin.campaign.change-status', ['campaign' => $campaign]),
            []
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

        $response = $this->patch(
            route('admin.campaign.change-status', ['campaign' => $campaignId]),
            []
        );

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $campaign = Campaign::factory()->create();

        $response = $this->patch(route('admin.campaign.change-status', ['campaign' => $campaign]));

        $response->assertUnauthorized();
    }
}
