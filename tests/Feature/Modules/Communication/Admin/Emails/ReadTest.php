<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Emails;

use Modules\Communication\Enums\EmailPermission;
use Modules\Communication\Models\Email;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;
    /**
     * Test authorized user can get email list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_email_list(): void
    {
        $this->authenticateWithPermission(EmailPermission::fromValue(EmailPermission::VIEW_COMMUNICATION_EMAIL));

        $this->brand->makeCurrent();

        $email = $this->brand->execute(function () {
            return Email::factory()->make();
        });
        $email->save();

        $response = $this->getJson(route('admin.communication.emails.index'));

        $response->assertOk();

        $emailOut = $email->toArray();
        unset($emailOut['user_id']);

        $response->assertJson([
            'data' => [$emailOut],
        ]);
    }

    /**
     * Test unauthorized user cant get email list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_cant_get_email_list(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $email = $this->brand->execute(function () {
            return Email::factory()->make();
        });
        $email->save();

        $response = $this->getJson(route('admin.communication.emails.index'));

        $response->assertForbidden();
    }

    /**
     * Test unauthorized user get email list.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_get_email_list(): void
    {
        $this->brand->makeCurrent();

        $email = $this->brand->execute(function () {
            return Email::factory()->make();
        });
        $email->save();

        $response = $this->getJson(route('admin.communication.emails.index'));

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user can get email by ID.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_email(): void
    {
        $this->authenticateWithPermission(EmailPermission::fromValue(EmailPermission::VIEW_COMMUNICATION_EMAIL));

        $this->brand->makeCurrent();

        $email = $this->brand->execute(function () {
            return Email::factory()->make();
        });
        $email->save();

        $response = $this->getJson(route('admin.communication.emails.show', ['email' => $email->id]));

        $response->assertOk();

        $emailOut = $email->toArray();
        unset($emailOut['user_id']);

        $response->assertJson(['data' => $emailOut]);
    }

    /**
     * Test authorized user can get email by ID.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_cant_get_email(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $email = $this->brand->execute(function () {
            return Email::factory()->make();
        });
        $email->save();

        $response = $this->getJson(route('admin.communication.emails.show', ['email' => $email->id]));

        $response->assertForbidden();
    }

    /**
     * Test authorized user can get not found email by ID.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_not_found_email(): void
    {
        $this->authenticateWithPermission(EmailPermission::fromValue(EmailPermission::VIEW_COMMUNICATION_EMAIL));

        $this->brand->makeCurrent();

        $emailId = Email::orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->getJson(route('admin.communication.emails.show', ['email' => $emailId]));

        $response->assertNotFound();
    }

    /**
     * Test unauthorized user can get email by ID.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_can_get_email(): void
    {
        $this->brand->makeCurrent();

        $email = $this->brand->execute(function () {
            return Email::factory()->make();
        });
        $email->save();

        $response = $this->getJson(route('admin.communication.emails.show', ['email' => $email->id]));

        $response->assertUnauthorized();
    }
}
