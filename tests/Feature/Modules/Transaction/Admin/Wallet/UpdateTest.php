<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin\Wallet;

use Modules\Currency\Models\Currency;
use Modules\Transaction\Enums\TransactionsWalletPermission;
use Modules\Transaction\Models\TransactionsWallet;
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
            TransactionsWalletPermission::fromValue(TransactionsWalletPermission::EDIT_TRANSACTION_WALLET)
        );

        $this->brand->makeCurrent();

        Currency::truncate();
        $transactionsWallet = TransactionsWallet::factory()->create();
        $transactionsWalletData = TransactionsWallet::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $response = $this->patch(route('admin.transaction-wallets.update', ['transaction_wallet' => $transactionsWallet]), $transactionsWalletData);

        $response->assertOk();
        $response->assertJson(['data' => $transactionsWalletData]);
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        Currency::truncate();
        $transactionsWallet = TransactionsWallet::factory()->create();
        $data = TransactionsWallet::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(
            route('admin.transaction-wallets.update', ['transaction_wallet' => $transactionsWallet]), $data->toArray()
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

        Currency::truncate();
        $transactionsWalletId = TransactionsWallet::orderByDesc('id')->first()?->id + 1 ?? 1;
        $data = TransactionsWallet::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(route('admin.transaction-wallets.update', ['transaction_wallet' => $transactionsWalletId]),
            $data->toArray());

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        Currency::truncate();
        $transactionsWallet = TransactionsWallet::factory()->create();

        $response = $this->patch(route('admin.transaction-wallets.update', ['transaction_wallet' => $transactionsWallet]));

        $response->assertUnauthorized();
    }
}
