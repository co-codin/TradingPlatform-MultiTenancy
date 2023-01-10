<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin\Wallet;

use Modules\Currency\Models\Currency;
use Modules\Transaction\Enums\WalletPermission;
use Modules\Transaction\Models\Wallet;
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
            WalletPermission::fromValue(WalletPermission::DELETE_WALLET)
        );

        $this->brand->makeCurrent();

        Currency::truncate();
        $transactionsWallet = Wallet::factory()->create();

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
        $transactionsWallet = Wallet::factory()->create();

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

        $transactionsWalletId = Wallet::orderByDesc('id')->first()?->id + 1 ?? 1;

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
        $transactionsWallet = Wallet::factory()->create();

        $response = $this->delete(route('admin.transaction-wallets.destroy', ['transaction_wallet' => $transactionsWallet]));

        $response->assertUnauthorized();
    }
}
