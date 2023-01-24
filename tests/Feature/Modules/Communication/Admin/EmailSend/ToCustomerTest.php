<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\EmailSend;

use Modules\Communication\Enums\EmailPermission;
use Modules\Communication\Models\Email;
use Modules\Customer\Models\Customer;
use Modules\Geo\Database\Seeders\GeoDatabaseSeeder;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ToCustomerTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(GeoDatabaseSeeder::class);
    }

    /**
     * Test authorized user can send email.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_send_email(): void
    {
        $this->authenticateWithPermission(EmailPermission::fromValue(EmailPermission::SEND_COMMUNICATION_EMAIL));

        $this->brand->makeCurrent();

        $email = $this->brand->execute(function () {
            return Email::factory()->make();
        });
        $email->save();

        $customer = $this->brand->execute(function () {
            return Customer::factory()->make();
        });
        $customer->save();

        $response = $this->postJson(route('admin.communication.email.send'), ['email_id' => $email->id]);

        $response->assertNoContent();
    }

    /**
     * Test authorized user can`t send email.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_cant_send_email(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $email = $this->brand->execute(function () {
            return Email::factory()->make();
        });
        $email->save();

        $customer = $this->brand->execute(function () {
            return Customer::factory()->make();
        });
        $customer->save();

        $response = $this->postJson(route('admin.communication.email.send'), ['email_id' => $email->id]);

        $response->assertForbidden();
    }

    /**
     * Test unauthorized user can`t send email.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $email = $this->brand->execute(function () {
            return Email::factory()->make();
        });
        $email->save();

        $customer = $this->brand->execute(function () {
            return Customer::factory()->make();
        });
        $customer->save();

        $response = $this->postJson(route('admin.communication.email.send'), ['email_id' => $email->id]);

        $response->assertUnauthorized();
    }
}
