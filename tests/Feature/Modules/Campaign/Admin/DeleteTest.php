<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Campaign\Admin;

use Modules\Campaign\Enums\CampaignPermission;
use Modules\Campaign\Models\Campaign;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class DeleteTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_delete(): void
    {
        $this->authenticateWithPermission(
            CampaignPermission::fromValue(CampaignPermission::DELETE_CAMPAIGN)
        );

        $this->brand->makeCurrent();

        $campaign = Campaign::factory()->create();

        $response = $this->delete(route('admin.campaign.destroy', ['campaign' => $campaign]));

        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function cant_delete(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $campaign = Campaign::factory()->create();

        $response = $this->delete(route('admin.campaign.destroy', ['campaign' => $campaign]));

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

        $response = $this->delete(route('admin.campaign.destroy', ['campaign' => $campaignId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $campaign = Campaign::factory()->create();

        $response = $this->delete(route('admin.campaign.destroy', ['campaign' => $campaign]));

        $response->assertUnauthorized();
    }
}
