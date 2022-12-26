<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin\Wallet;

use Modules\Currency\Models\Currency;
use Modules\Transaction\Enums\TransactionsWalletPermission;
use Modules\Transaction\Models\TransactionsWallet;
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
            TransactionsWalletPermission::fromValue(TransactionsWalletPermission::DELETE_TRANSACTION_WALLET)
        );

        $this->brand->makeCurrent();

        Currency::truncate();
        $transactionsWallet = TransactionsWallet::factory()->create();

        $response = $this->delete(route('admin.transaction-wallets.destroy', ['transaction_wallet' => $transactionsWallet]));

        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function cant_delete(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        Currency::truncate();
        $transactionsWallet = TransactionsWallet::factory()->create();

        $response = $this->delete(route('admin.transaction-wallets.destroy', ['transaction_wallet' => $transactionsWallet]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $transactionsWalletId = TransactionsWallet::orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->delete(route('admin.transaction-wallets.destroy', ['transaction_wallet' => $transactionsWalletId]));

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

        $response = $this->delete(route('admin.transaction-wallets.destroy', ['transaction_wallet' => $transactionsWallet]));

        $response->assertUnauthorized();
    }
}
