<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\EmailTemplates;

use Modules\Communication\Enums\EmailTemplatesPermission;
use Modules\Communication\Models\EmailTemplates;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can update template list.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_update_email_template(): void
    {
        $this->authenticateWithPermission(EmailTemplatesPermission::fromValue(EmailTemplatesPermission::EDIT_COMMUNICATION_EMAIL_TEMPLATE));

        $this->brand->makeCurrent();

        $emailtemplate = EmailTemplates::factory()->create();

        $data = EmailTemplates::factory()->make();

        $response = $this->patchJson(route('admin.email-templates.update', ['email_template' => $emailtemplate->id]), $data->toArray());

        $response->assertOk();

        $response->assertJson([
            'data' => $data->toArray(),
        ]);
    }
    /**
     * Test authorized user can`t update template list.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_cant_update_email_template(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $emailtemplate = EmailTemplates::factory()->create();

        $data = EmailTemplates::factory()->make();

        $response = $this->patchJson(route('admin.email-templates.update', ['email_template' => $emailtemplate->id]), $data->toArray());

        $response->assertForbidden();
    }
    /**
     * Test authorized user can update not found template list.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_update_not_found_email_template(): void
    {
        $this->authenticateWithPermission(EmailTemplatesPermission::fromValue(EmailTemplatesPermission::EDIT_COMMUNICATION_EMAIL_TEMPLATE));

        $this->brand->makeCurrent();

        $emailtemplateId = EmailTemplates::orderByDesc('id')->first()?->id + 1 ?? 1;

        $data = EmailTemplates::factory()->make();

        $response = $this->patchJson(route('admin.email-templates.update', ['email_template' => $emailtemplateId]), $data->toArray());

        $response->assertNotFound();
    }
    /**
     * Test unauthorized user can update template list.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $emailtemplate = EmailTemplates::factory()->create();

        $data = EmailTemplates::factory()->make();

        $response = $this->patchJson(route('admin.email-templates.update', ['email_template' => $emailtemplate->id]), $data->toArray());

        $response->assertUnauthorized();
    }
}
