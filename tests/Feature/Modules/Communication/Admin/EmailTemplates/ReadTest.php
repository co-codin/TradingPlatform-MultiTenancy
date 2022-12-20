<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\EmailTemplates;

use Modules\Communication\Enums\EmailTemplatesPermission;
use Modules\Communication\Models\EmailTemplates;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can get email template list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_email_template_list(): void
    {
        $this->authenticateWithPermission(EmailTemplatesPermission::fromValue(EmailTemplatesPermission::VIEW_COMMUNICATION_EMAIL_TEMPLATE));

        $this->brand->makeCurrent();

        $emailtemplate = EmailTemplates::factory()->create();

        $response = $this->getJson(route('admin.email-templates.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [$emailtemplate->toArray()],
        ]);
    }

    /**
     * Test unauthorized user cant get email template list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_cant_get_email_template_list(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        EmailTemplates::factory()->create();

        $response = $this->getJson(route('admin.email-templates.index'));

        $response->assertForbidden();
    }

    /**
     * Test unauthorized user get email template list.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_get_email_template_list(): void
    {
        $this->brand->makeCurrent();

        EmailTemplates::factory()->create();

        $response = $this->getJson(route('admin.email-templates.index'));

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user can get email template by ID.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_email_template(): void
    {
        $this->authenticateWithPermission(EmailTemplatesPermission::fromValue(EmailTemplatesPermission::VIEW_COMMUNICATION_EMAIL_TEMPLATE));

        $this->brand->makeCurrent();

        $emailtemplate = EmailTemplates::factory()->create();

        $response = $this->getJson(route('admin.email-templates.show', ['email_template' => $emailtemplate->id]));

        $response->assertOk();

        $response->assertJson(['data' => $emailtemplate->toArray()]);
    }

    /**
     * Test authorized user can get email template by ID.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_cant_get_email_template(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $emailtemplate = EmailTemplates::factory()->create();

        $response = $this->getJson(route('admin.email-templates.show', ['email_template' => $emailtemplate->id]));

        $response->assertForbidden();
    }

    /**
     * Test authorized user can get not found email template by ID.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_not_found_email_template(): void
    {
        $this->authenticateWithPermission(EmailTemplatesPermission::fromValue(EmailTemplatesPermission::VIEW_COMMUNICATION_EMAIL_TEMPLATE));

        $this->brand->makeCurrent();

        $emailtemplateId = EmailTemplates::orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->getJson(route('admin.email-templates.show', ['email_template' => $emailtemplateId]));

        $response->assertNotFound();
    }

    /**
     * Test unauthorized user can get email template by ID.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_can_get_email_template(): void
    {
        $this->brand->makeCurrent();

        $emailtemplate = EmailTemplates::factory()->create();

        $response = $this->getJson(route('admin.email-templates.show', ['email_template' => $emailtemplate->id]));

        $response->assertUnauthorized();
    }
}
