<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Emails;

use Modules\Communication\Enums\EmailPermission;
use Modules\Communication\Models\Email;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;
    /**
     * Test authorized user can update email.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_update_email(): void
    {
        $this->authenticateWithPermission(EmailPermission::fromValue(EmailPermission::EDIT_COMMUNICATION_EMAIL));

        $this->brand->makeCurrent();

        $email = $this->brand->execute(function () {
            return Email::factory()->make();
        });
        $email->save();

        $data = Email::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patchJson(route('admin.emails.update', ['email' => $email->id]), $data->toArray());

        $response->assertOk();

        $dataOut = $data->toArray();
        unset($dataOut['user_id']);

        $response->assertJson([
            'data' => $dataOut,
        ]);
    }
    /**
     * Test authorized user can`t update email.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_cant_update_email(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $email = $this->brand->execute(function () {
            return Email::factory()->make();
        });
        $email->save();

        $data = Email::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patchJson(route('admin.emails.update', ['email' => $email->id]), $data->toArray());

        $response->assertForbidden();
    }
    /**
     * Test authorized user can update not found email.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_update_not_found_email(): void
    {
        $this->authenticateWithPermission(EmailPermission::fromValue(EmailPermission::EDIT_COMMUNICATION_EMAIL));

        $this->brand->makeCurrent();

        $emailId = Email::orderByDesc('id')->first()?->id + 1 ?? 1;

        $data = Email::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patchJson(route('admin.emails.update', ['email' => $emailId]), $data->toArray());

        $response->assertNotFound();
    }
    /**
     * Test unauthorized user can update email.
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

        $data = Email::factory()->make();

        $response = $this->patchJson(route('admin.emails.update', ['email' => $email->id]), $data->toArray());

        $response->assertUnauthorized();
    }
}
