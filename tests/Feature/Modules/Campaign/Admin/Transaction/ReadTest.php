<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Campaign\Admin\Transaction;

use Modules\Campaign\Enums\CampaignTransactionPermission;
use Modules\Campaign\Models\CampaignTransaction;
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
        $this->authenticateWithPermission(CampaignTransactionPermission::fromValue(CampaignTransactionPermission::VIEW_CAMPAIGN_TRANSACTION));

        $this->brand->makeCurrent();

        $campaignTransaction = CampaignTransaction::factory()->create()->toArray();

        $response = $this->getJson(route('admin.campaign-transaction.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [$campaignTransaction],
        ]);
    }

    /**
     * @test
     */
    public function can_not_view_any(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $response = $this->get(route('admin.campaign-transaction.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_view(): void
    {
        $this->authenticateWithPermission(
            CampaignTransactionPermission::fromValue(CampaignTransactionPermission::VIEW_CAMPAIGN_TRANSACTION)
        );

        $this->brand->makeCurrent();

        $campaignTransaction = CampaignTransaction::factory()->create();

        $response = $this->get(route('admin.campaign-transaction.show', ['campaign_transaction' => $campaignTransaction]));

        $response->assertOk();
        $response->assertJson(['data' => $campaignTransaction->toArray()]);
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $campaignTransaction = CampaignTransaction::factory()->create();

        $response = $this->get(route('admin.campaign-transaction.show', ['campaign_transaction' => $campaignTransaction]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $campaignTransactionId = CampaignTransaction::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('admin.campaign-transaction.show', ['campaign_transaction' => $campaignTransactionId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized_view_any(): void
    {
        $response = $this->get(route('admin.campaign-transaction.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $this->brand->makeCurrent();

        $campaignTransaction = CampaignTransaction::factory()->create();

        $response = $this->get(route('admin.campaign-transaction.show', ['campaign_transaction' => $campaignTransaction]));

        $response->assertUnauthorized();
    }
}
