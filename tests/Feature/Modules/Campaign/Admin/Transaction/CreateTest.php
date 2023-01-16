<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Campaign\Admin\Transaction;

use Modules\Campaign\Enums\CampaignTransactionPermission;
use Modules\Campaign\Models\CampaignTransaction;
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
            CampaignTransactionPermission::fromValue(CampaignTransactionPermission::CREATE_CAMPAIGN_TRANSACTION)
        );

        $this->brand->makeCurrent();

        $data = CampaignTransaction::factory()->make()->toArray();

        $response = $this->post(route('admin.campaign-transaction.store'), $data);

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

        $data = CampaignTransaction::factory()->make()->toArray();

        $response = $this->post(route('admin.campaign-transaction.store'), $data);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('admin.campaign-transaction.store'));

        $response->assertUnauthorized();
    }
}
