<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Campaign\Admin\Transaction;

use Modules\Campaign\Enums\CampaignTransactionPermission;
use Modules\Campaign\Models\CampaignTransaction;
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
            CampaignTransactionPermission::fromValue(CampaignTransactionPermission::EDIT_CAMPAIGN_TRANSACTION)
        );

        $this->brand->makeCurrent();

        $campaignTransaction = CampaignTransaction::factory()->create();

        $campaignTransactionData = CampaignTransaction::factory()->make()->toArray();

        $response = $this->patchJson(route('admin.campaign-transaction.update', ['campaign_transaction' => $campaignTransaction]), $campaignTransactionData);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => array_keys($campaignTransactionData),
        ]);
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $campaignTransaction = CampaignTransaction::factory()->create();

        $data = CampaignTransaction::factory()->make();

        $response = $this->patch(
            route('admin.campaign-transaction.update', ['campaign_transaction' => $campaignTransaction]),
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

        $campaignTransactionId = CampaignTransaction::orderByDesc('id')->first()?->id + 1 ?? 1;
        $data = CampaignTransaction::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(
            route('admin.campaign-transaction.update', ['campaign_transaction' => $campaignTransactionId]),
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

        $campaignTransaction = CampaignTransaction::factory()->create();

        $response = $this->patch(route('admin.campaign-transaction.update', ['campaign_transaction' => $campaignTransaction]));

        $response->assertUnauthorized();
    }
}
