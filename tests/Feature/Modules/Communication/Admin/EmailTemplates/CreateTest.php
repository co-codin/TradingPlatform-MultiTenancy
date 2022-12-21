<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\EmailTemplates;

use Modules\Communication\Enums\EmailTemplatesPermission;
use Modules\Communication\Models\EmailTemplates;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can create email template.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create_email_template(): void
    {
        $this->authenticateWithPermission(EmailTemplatesPermission::fromValue(EmailTemplatesPermission::CREATE_COMMUNICATION_EMAIL_TEMPLATE));

        $this->brand->makeCurrent();

        $data = EmailTemplates::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $response = $this->postJson(route('admin.communication.email-templates.store'), $data);

        $response->assertCreated();

        $response->assertJson(['data' => $data]);
    }

    /**
     * Test authorized user can`t create email template.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_cant_create_email_template(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $data = EmailTemplates::factory()->make()->toArray();

        $response = $this->postJson(route('admin.communication.email-templates.store'), $data);

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
        $data = EmailTemplates::factory()->make()->toArray();

        $response = $this->postJson(route('admin.communication.email-templates.store'), $data);

        $response->assertUnauthorized();
    }
}
