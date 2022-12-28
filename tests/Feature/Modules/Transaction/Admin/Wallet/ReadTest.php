<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin\Wallet;

use Modules\Currency\Models\Currency;
use Modules\Transaction\Enums\TransactionsWalletPermission;
use Modules\Transaction\Models\TransactionsWallet;
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
        $this->authenticateWithPermission(
            TransactionsWalletPermission::fromValue(TransactionsWalletPermission::VIEW_TRANSACTION_WALLET)
        );

        $this->brand->makeCurrent();

        Currency::truncate();
        $transactionsWallet = TransactionsWallet::factory(10)->create();

        $response = $this->get(route('admin.transaction-wallets.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => $transactionsWallet->only('id')->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function can_not_view_any(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $response = $this->get(route('admin.transaction-wallets.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_view(): void
    {
        $this->authenticateWithPermission(
            TransactionsWalletPermission::fromValue(TransactionsWalletPermission::VIEW_TRANSACTION_WALLET)
        );

        $this->brand->makeCurrent();

        Currency::truncate();
        $transactionsWallet = TransactionsWallet::factory()->create();

        $response = $this->get(route('admin.transaction-wallets.show', ['transaction_wallet' => $transactionsWallet]));

        $response->assertOk();
        $response->assertJson(['data' => $transactionsWallet->toArray()]);
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        Currency::truncate();
        $transactionsWallet = TransactionsWallet::factory()->create();

        $response = $this->get(route('admin.transaction-wallets.show', ['transaction_wallet' => $transactionsWallet]));

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
        $transactionsWalletId = TransactionsWallet::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('admin.transaction-wallets.show', ['transaction_wallet' => $transactionsWalletId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized_view_any(): void
    {
        $response = $this->get(route('admin.transaction-wallets.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $this->brand->makeCurrent();

        Currency::truncate();
        $transactionsWallet = TransactionsWallet::factory()->create();

        $response = $this->get(route('admin.transaction-wallets.show', ['transaction_wallet' => $transactionsWallet]));

        $response->assertUnauthorized();
    }
}
