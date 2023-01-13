<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Campaign\Admin;

use Modules\Campaign\Enums\CampaignPermission;
use Modules\Campaign\Models\Campaign;
use Modules\Campaign\Models\CampaignCountry;
use Modules\Geo\Models\Country;
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

        $campaign = $this->brand->execute(function () {
            return Campaign::factory()->make();
        });
        $campaign->save();

        $campaignData = Campaign::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $countries = Country::query()->limit(3)->get();

        $campaignCountryData = [];

        foreach ($countries as $country) {
            $campaignCountryData[$country->id] = CampaignCountry::factory()->create([
                'campaign_id' => $campaign->id,
                'country_id' => $country->id,
            ]);
        }

        $response = $this->patchJson(route('admin.campaign.update', ['campaign' => $campaign->id]), array_merge($campaignData, [
            'countries' => $campaignCountryData,
        ]));

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

        $this->brand->makeCurrent();

        $campaign = $this->brand->execute(function () {
            return Campaign::factory()->make();
        });
        $campaign->save();

        $data = Campaign::factory()->make();

        $this->brand->makeCurrent();

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

        $this->brand->makeCurrent();

        $campaignId = Campaign::orderByDesc('id')->first()?->id + 1 ?? 1;
        $data = Campaign::factory()->make();

        $this->brand->makeCurrent();

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
        $this->brand->makeCurrent();

        $campaign = $this->brand->execute(function () {
            return Campaign::factory()->make();
        });
        $campaign->save();

        $response = $this->patch(route('admin.campaign.update', ['campaign' => $campaign]));

        $response->assertUnauthorized();
    }
}
