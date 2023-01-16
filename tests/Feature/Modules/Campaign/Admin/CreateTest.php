<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Campaign\Admin;

use Modules\Campaign\Enums\CampaignPermission;
use Modules\Campaign\Models\Campaign;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function can_create(): void
    {
        $this->authenticateWithPermission(
            CampaignPermission::fromValue(CampaignPermission::CREATE_CAMPAIGN)
        );

        $data = Campaign::factory()->make()->toArray();

        $response = $this->post(route('admin.campaign.store'), $data);

        $response->assertCreated();
        $response->assertJson(['data' => $data]);
    }

    /**
     * @test
     */
    public function can_not_create(): void
    {
        $this->authenticateUser();

        $data = Campaign::factory()->make()->toArray();

        $response = $this->post(route('admin.campaign.store'), $data);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('admin.campaign.store'));

        $response->assertUnauthorized();
    }
}
