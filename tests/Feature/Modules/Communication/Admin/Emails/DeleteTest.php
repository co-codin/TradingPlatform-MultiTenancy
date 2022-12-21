<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Emails;

use Modules\Communication\Enums\EmailPermission;
use Modules\Communication\Models\Email;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class DeleteTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can delete email.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_delete_email(): void
    {
        $this->authenticateWithPermission(EmailPermission::fromValue(EmailPermission::DELETE_COMMUNICATION_EMAIL));

        $this->brand->makeCurrent();

        $email = $this->brand->execute(function () {
            return Email::factory()->make();
        });
        $email->save();

        $response = $this->deleteJson(route('admin.communication.emails.destroy', ['email' => $email->id]));

        $response->assertNoContent();
    }

    /**
     * Test authorized user can`t delete email.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_cant_delete_email(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $email = $this->brand->execute(function () {
            return Email::factory()->make();
        });
        $email->save();


        $response = $this->deleteJson(route('admin.communication.emails.destroy', ['email' => $email->id]));

        $response->assertForbidden();
    }

    /**
     * Test authorized user can delete not found email.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_delete_not_found_email(): void
    {
        $this->authenticateWithPermission(EmailPermission::fromValue(EmailPermission::DELETE_COMMUNICATION_EMAIL));

        $this->brand->makeCurrent();

        $email = Email::orderByDesc('id')->first()?->id + 1 ?? 1;
        $response = $this->delete(route('admin.communication.emails.destroy', ['email' => $email]));

        $response->assertNotFound();
    }

    /**
     * Test unauthorized user can`t delete email.
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


        $response = $this->patchJson(route('admin.communication.emails.destroy', ['email' => $email->id]));

        $response->assertUnauthorized();
    }
}
