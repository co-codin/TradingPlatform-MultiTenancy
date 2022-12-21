<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Comment;

use Illuminate\Support\Facades\Storage;
use Modules\Communication\Enums\CommentPermission;
use Modules\Communication\Enums\CommunicationExtensionPermission;
use Modules\Communication\Models\Comment;
use Modules\Communication\Models\CommunicationExtension;
use Modules\Media\Models\Attachment;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Spatie\Multitenancy\Landlord;
use Spatie\Permission\PermissionRegistrar;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_create(): void
    {
        try {

            $this->authenticateWithPermission(CommentPermission::fromValue(CommentPermission::CREATE_COMMENT));

            $this->brand->makeCurrent();

            $attachments = Attachment::factory(3)
                ->create()
                ->map(function ($attachment) {
                    dd($attachment->path);
                    return Storage::get($attachment->path);
                })
                ->toArray();
dd($attachments);
            $data = array_merge(Comment::factory()->make()->toArray(), [
                'attachments' => $attachments,
            ]);
dd($data);
            $response = $this->post(route('admin.comments.store'), $data);
            dd($response->json('message'));
            $response->assertCreated();
            $response->assertJson(['data' => $data->toArray()]);
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function can_not_create(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $response = $this->post(route('admin.communication.extensions.store'),
            CommunicationExtension::factory()->make()->toArray());

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('admin.communication.extensions.store'));

        $response->assertUnauthorized();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
}
