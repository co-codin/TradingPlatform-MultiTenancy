<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\EmailTemplates;

use Modules\Communication\Enums\EmailTemplatesPermission;
use Modules\Communication\Models\EmailTemplates;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class DeleteTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can delete email template.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_delete_email_template(): void
    {
        $this->authenticateWithPermission(EmailTemplatesPermission::fromValue(EmailTemplatesPermission::DELETE_COMMUNICATION_EMAIL_TEMPLATE));

        $this->brand->makeCurrent();

        $emailtemplate = EmailTemplates::factory()->create();

        $response = $this->deleteJson(route('admin.email-templates.destroy', ['email_template' => $emailtemplate->id]));

        $response->assertNoContent();
    }

    /**
     * Test authorized user can`t delete email template.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_cant_delete_email_template(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $emailtemplate = EmailTemplates::factory()->create();

        $response = $this->deleteJson(route('admin.email-templates.destroy', ['email_template' => $emailtemplate->id]));

        $response->assertForbidden();
    }

    /**
     * Test authorized user can delete not found email template.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_delete_not_found_email_template(): void
    {
        $this->authenticateWithPermission(EmailTemplatesPermission::fromValue(EmailTemplatesPermission::DELETE_COMMUNICATION_EMAIL_TEMPLATE));

        $this->brand->makeCurrent();

        $emailtemplate = EmailTemplates::orderByDesc('id')->first()?->id + 1 ?? 1;
        $response = $this->delete(route('admin.email-templates.destroy', ['email_template' => $emailtemplate]));

        $response->assertNotFound();
    }

    /**
     * Test unauthorized user can`t delete email template.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $emailtemplate = EmailTemplates::factory()->create();

        $response = $this->patchJson(route('admin.email-templates.destroy', ['email_template' => $emailtemplate->id]));

        $response->assertUnauthorized();
    }
}
