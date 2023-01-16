<?php

namespace Tests\Feature\Modules\Customer\Affiliate\FTD;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Customer\Models\Customer;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\User\Models\User;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test affiliate user can get ftd customer list.
     *
     * @return void
     *
     * @test
     */
    public function affiliate_user_can_get_ftd_customer_list(): void
    {
        $user = User::factory()->create();

        $affiliateToken = $user->affiliateToken()->create([
            'token' => Str::random(),
            'ip' => request()->ip(),
        ]);

        $this->withHeader('AffiliateToken', $affiliateToken->token);

        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () use ($user) {
            return Customer::factory()->make([
                'affiliate_user_id' => $user->id,
                'is_ftd' => true
            ]);
        });

        $customers->save();

        $user->assignRole(
            Role::where('name', DefaultRole::AFFILIATE)->first()
                ?? Role::factory()->create([
                    'name' => DefaultRole::AFFILIATE,
                ])
        );

        $response = $this->getJson(route('affiliate.ftd-customers.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [Arr::only($customers->toArray(), [
                'id',
                'email',
                'is_ftd',
                'first_deposit_date',
                'created_at'
            ])],
        ]);
    }

    /**
     * Test affiliate user send wrong affiliate token.
     *
     * @return void
     *
     * @test
     */
    public function affiliate_user_send_wrong_affiliate_token(): void
    {
        $user = User::factory()->create();

        $user->affiliateToken()->create([
            'token' => Str::random(),
            'ip' => request()->ip(),
        ]);

        $this->withHeader('AffiliateToken', 'wrong_affiliate_token');

        $response = $this->getJson(route('affiliate.ftd-customers.index'));

        $response->assertNotFound();
    }
}
