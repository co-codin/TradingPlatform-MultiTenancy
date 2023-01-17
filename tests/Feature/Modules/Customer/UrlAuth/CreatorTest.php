<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer\UrlAuth;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Modules\Customer\Models\Customer;
use Modules\Customer\Services\UrlAuthCreator;
use Tests\BrandTestCase;

final class CreatorTest extends BrandTestCase
{
    private Customer $customer;
    private UrlAuthCreator $creator;

    /**
     * @test
     */
    public function success(): void
    {
        $spy = Cache::spy();

        $url = $this->creator->create($this->customer->id, $this->brand->id);

        $queryString = parse_url($url, PHP_URL_QUERY);
        self::assertNotFalse($queryString);
        parse_str($queryString, $queryParams);
        self::assertArrayHasKey('key', $queryParams);

        $spy->shouldHaveReceived('put', [
            "url-auth:{$queryParams['key']}",
            ['customerId' => $this->customer->id, 'brandId' => $this->brand->id],
            Mockery::type(Carbon::class),
        ])->once();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->brand->makeCurrent();

        $customer = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customer->save();

        $this->customer = $customer;

        $this->creator = $this->app->make(UrlAuthCreator::class);
    }
}
