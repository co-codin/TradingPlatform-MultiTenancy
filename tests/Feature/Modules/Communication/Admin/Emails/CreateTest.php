<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Emails;

use Modules\Communication\Enums\EmailPermission;
use Modules\Communication\Models\Email;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can create email.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create_email(): void
    {
        $this->authenticateWithPermission(EmailPermission::fromValue(EmailPermission::CREATE_COMMUNICATION_EMAIL));

        $this->brand->makeCurrent();

        $data = Email::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $response = $this->postJson(route('admin.emails.store'), $data);

        $response->assertCreated();

        unset($data['user_id']);

        $response->assertJson(['data' => $data]);
    }

    /**
     * Test authorized user can`t create email template.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_cant_create_email(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $data = Email::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $response = $this->postJson(route('admin.emails.store'), $data);

        $response->assertForbidden();
    }

    /**
     * Test unauthorized user can`t create email template.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $data = Email::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $response = $this->postJson(route('admin.emails.store'), $data);

        $response->assertUnauthorized();
    }
}
