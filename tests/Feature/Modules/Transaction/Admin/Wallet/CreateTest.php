<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin\Wallet;

use Modules\Currency\Models\Currency;
use Modules\Transaction\Enums\TransactionsWalletPermission;
use Modules\Transaction\Models\TransactionsWallet;
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
            TransactionsWalletPermission::fromValue(TransactionsWalletPermission::CREATE_TRANSACTION_WALLET)
        );

        $this->brand->makeCurrent();

        Currency::truncate();
        $data = TransactionsWallet::factory()->make()->toArray();

        $response = $this->post(route('admin.transaction-wallets.store'), $data);

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

        Currency::truncate();
        $data = TransactionsWallet::factory()->make()->toArray();

        $response = $this->post(route('admin.transaction-wallets.store'), $data);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('admin.transaction-wallets.store'));

        $response->assertUnauthorized();
    }
}
