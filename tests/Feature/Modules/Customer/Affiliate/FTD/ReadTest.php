<?php

namespace Tests\Feature\Modules\Customer\Affiliate\FTD;

use Illuminate\Support\Str;
use Modules\Customer\Enums\CustomerPermission;
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
     * Test authorized user can get ftd customer list.
     *
     * @return void
     *
     * @test
     */
    public function affiliate_user_can_get_ftd_customer_list(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::VIEW_CUSTOMERS));

        $affiliateToken = $this->user->affiliateToken()->create([
            'token' => Str::random(),
            'ip' => request()->ip(),
        ]);

        $this->withHeader('AffiliateToken', $affiliateToken->token);

        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make([
                'affiliate_user_id' => $this->user->id,
                'is_ftd' => true
            ]);
        });

        $customers->save();

        $this->user->assignRole(
            Role::where('name', DefaultRole::AFFILIATE)->first()
                ?? Role::factory()->create([
                    'name' => DefaultRole::AFFILIATE,
                ])
        );

        $response = $this->getJson(route('affiliate.ftd-customers.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [$customers->toArray()],
        ]);
    }

    /**
     * Test unauthorized user cant get ftd customer list.
     *
     * @return void
     *
     * @test
     */
    public function affiliate_user_cant_get_ftd_customer_list(): void
    {
        $this->authenticateUser();

        $affiliateToken = $this->user->affiliateToken()->create([
            'token' => Str::random(),
            'ip' => request()->ip(),
        ]);

        $this->withHeader('AffiliateToken', $affiliateToken->token);

        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make([
                'affiliate_user_id' => $this->user->id,
                'is_ftd' => true
            ]);
        });

        $customers->save();

        $response = $this->getJson(route('affiliate.ftd-customers.index'));

        $response->assertForbidden();
    }

    /**
     * Test unauthorized user get ftd customer list.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_get_ftd_customer_list(): void
    {
        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make([
                'affiliate_user_id' => 1,
                'is_ftd' => true
            ]);
        });

        $customers->save();

        $response = $this->getJson(route('affiliate.ftd-customers.index'));

        $response->assertUnauthorized();
    }
}
