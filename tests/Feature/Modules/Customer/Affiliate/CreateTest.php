<?php

namespace Tests\Feature\Modules\Customer\Affiliate;

use Illuminate\Support\Str;
use Modules\Campaign\Models\Campaign;
use Modules\Currency\Models\Currency;
use Modules\Customer\Models\Customer;
use Modules\Geo\Database\Seeders\GeoDatabaseSeeder;
use Modules\Geo\Models\Country;
use Modules\Language\Models\Language;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\User\Models\User;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Symfony\Component\HttpFoundation\Response;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(GeoDatabaseSeeder::class);
    }

    /**
     * Test affiliate user can create customer.
     *
     * @return void
     *
     * @test
     */
    public function affiliate_user_can_create_customer(): void
    {
        $user = User::factory()->create();

        $user->assignRole(
            Role::where('name', DefaultRole::AFFILIATE)->first()
                ?? Role::factory()->create([
                    'name' => DefaultRole::AFFILIATE,
                ])
        );

        $affiliateToken = $user->affiliateToken()->create([
            'token' => Str::random(),
            'ip' => request()->ip(),
        ]);

        $this->withHeader('AffiliateToken', $affiliateToken->token);

        $this->brand->makeCurrent();

        $campaign = Campaign::factory()->create([
            'affiliate_id' => $user->id,
            'is_active' => true,
        ]);

        $data = $this->brand->execute(function () use ($user) {
            return Customer::factory()->make([
                'affiliate_user_id' => $user->id,
            ]);
        })->toArray();

        $data['country'] = Country::find($data['country_id'])->name;
        $data['language'] = Language::find($data['language_id'])->name;
        $data['currency'] = Currency::find($data['currency_id'])->iso3;
        $data['campaign_id'] = $campaign->id;
        $data['tenant'] = $this->brand->domain;

        $response = $this->postJson(route('affiliate.customers.store'), $data);

        $response->assertCreated();

        $response->assertJsonStructure(['data' => [
            'password',
            'link',
        ]]);
    }

    /**
     * Test affiliate user can create customer with is_active false campaign.
     *
     * @return void
     *
     * @test
     */
    public function affiliate_user_can_create_customer_with_is_active_false_campaign(): void
    {
        $user = User::factory()->create();

        $user->assignRole(
            Role::where('name', DefaultRole::AFFILIATE)->first()
                ?? Role::factory()->create([
                    'name' => DefaultRole::AFFILIATE,
                ])
        );

        $affiliateToken = $user->affiliateToken()->create([
            'token' => Str::random(),
            'ip' => request()->ip(),
        ]);

        $this->withHeader('AffiliateToken', $affiliateToken->token);

        $this->brand->makeCurrent();

        $campaign = Campaign::factory()->create([
            'affiliate_id' => $user->id,
            'is_active' => false,
        ]);

        $data = $this->brand->execute(function () use ($user) {
            return Customer::factory()->make([
                'affiliate_user_id' => $user->id,
            ]);
        })->toArray();

        $data['country'] = Country::find($data['country_id'])->name;
        $data['language'] = Language::find($data['language_id'])->name;
        $data['currency'] = Currency::find($data['currency_id'])->iso3;
        $data['campaign_id'] = $campaign->id;
        $data['tenant'] = $this->brand->domain;

        $response = $this->postJson(route('affiliate.customers.store'), $data);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Test affiliate user create customer with wrong affiliate token.
     *
     * @return void
     *
     * @test
     */
    public function affiliate_user_create_customer_with_wrong_affiliate_token(): void
    {
        $user = User::factory()->create();

        $user->assignRole(
            Role::where('name', DefaultRole::AFFILIATE)->first()
                ?? Role::factory()->create([
                    'name' => DefaultRole::AFFILIATE,
                ])
        );

        $user->affiliateToken()->create([
            'token' => Str::random(),
            'ip' => request()->ip(),
        ]);

        $this->withHeader('AffiliateToken', 'wrong_affiliate_token');

        $this->brand->makeCurrent();

        $campaign = Campaign::factory()->create([
            'affiliate_id' => $user->id,
            'is_active' => true,
        ]);

        $data = $this->brand->execute(function () use ($user) {
            return Customer::factory()->make([
                'affiliate_user_id' => $user->id,
            ]);
        })->toArray();

        $data['country'] = Country::find($data['country_id'])->name;
        $data['language'] = Language::find($data['language_id'])->name;
        $data['currency'] = Currency::find($data['currency_id'])->iso3;
        $data['campaign_id'] = $campaign->id;
        $data['tenant'] = $this->brand->domain;

        $response = $this->postJson(route('affiliate.customers.store'), $data);

        $response->assertNotFound();
    }
}
