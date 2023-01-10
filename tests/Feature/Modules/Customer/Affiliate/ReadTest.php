<?php

namespace Tests\Feature\Modules\Customer\Affiliate;

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
     * Test authorized user can get customer list.
     *
     * @return void
     *
     * @test
     */
    public function affiliate_user_can_get_customer_list(): void
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
            ]);
        });

        $customers->save();

        $this->user->assignRole(
            Role::where('name', DefaultRole::AFFILIATE)->first()
            ?? Role::factory()->create([
                'name' => DefaultRole::AFFILIATE,
            ])
        );

        $response = $this->getJson(route('affiliate.customers.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [$customers->toArray()],
        ]);
    }

    /**
     * Test unauthorized user cant get customer list.
     *
     * @return void
     *
     * @test
     */
    public function affiliate_user_cant_get_customer_list(): void
    {
        $this->authenticateUser();

        $affiliateToken = $this->user->affiliateToken()->create([
            'token' => Str::random(),
            'ip' => request()->ip(),
        ]);

        $this->withHeader('AffiliateToken', $affiliateToken->token);

        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customers->save();

        $response = $this->getJson(route('affiliate.customers.index'));

        $response->assertForbidden();
    }

    /**
     * Test unauthorized user get customer list.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_get_customer_list(): void
    {
        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customers->save();

        $response = $this->getJson(route('affiliate.customers.index'));

        $response->assertUnauthorized();
    }
}
