<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Campaign\Admin;

use Modules\Campaign\Enums\CampaignPermission;
use Modules\Campaign\Models\Campaign;
use Modules\Campaign\Models\CampaignCountry;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_create(): void
    {
        $this->authenticateWithPermission(
            CampaignPermission::fromValue(CampaignPermission::CREATE_CAMPAIGN)
        );

        $this->brand->makeCurrent();

        $data = Campaign::factory()->make()->toArray();
        $campaignCountryData = CampaignCountry::factory()->make()->toArray();

        $response = $this->post(route('admin.campaign.store'), array_merge($data, [
            'countries' => [
                array_merge($campaignCountryData, [
                    'id' => $campaignCountryData['country_id'],
                ]),
            ],
        ]));

        $response->assertCreated();
        $response->assertJson(['data' => $data]);
    }

    /**
     * @test
     */
    public function can_not_create(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $data = Campaign::factory()->make()->toArray();

        $response = $this->post(route('admin.campaign.store'), $data);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $this->brand->makeCurrent();

        $response = $this->post(route('admin.campaign.store'));

        $response->assertUnauthorized();
    }
}
