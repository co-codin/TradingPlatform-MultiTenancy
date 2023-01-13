<?php

declare(strict_types=1);

namespace tests\Feature\Modules\Transaction\Customer;

use Modules\Currency\Models\Currency;
use Modules\Customer\Models\Customer;
use Modules\Transaction\Enums\TransactionMt5TypeEnum;
use Modules\Transaction\Enums\TransactionType;
use Modules\Transaction\Models\Transaction;
use Modules\Transaction\Models\TransactionsMt5Type;
use Modules\Transaction\Models\Wallet;
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
        $this->authenticateCustomer();

        $this->brand->makeCurrent();

        $data = Transaction::factory()->make([
            'type' => TransactionType::WITHDRAWAL,
            'mt5_type_id' => TransactionsMt5Type::firstWhere('name', TransactionMt5TypeEnum::BALANCE)?->id,
            'wallet_id' => Wallet::factory()->create([
                'currency_id' => Currency::where('iso3', 'USD')->first()->id,
            ])->id,
            'customer_id' => Customer::factory()->create([
                'balance' => 100,
            ])->id,
        ])->toArray();

        unset($data['worker_id']);
        unset($data['creator_id']);
        unset($data['status_id']);

        $response = $this->post(route('customer.transactions.store'), $data);

        $response->assertCreated();
        $response->assertJson(['data' => $data]);
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('customer.transactions.store'));

        $response->assertUnauthorized();
    }
}
