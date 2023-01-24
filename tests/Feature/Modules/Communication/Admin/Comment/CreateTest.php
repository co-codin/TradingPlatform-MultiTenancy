<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Comment;

use Illuminate\Http\UploadedFile;
use Modules\Communication\Enums\CommentPermission;
use Modules\Communication\Models\Comment;
use Modules\Geo\Database\Seeders\GeoDatabaseSeeder;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(GeoDatabaseSeeder::class);
    }

    /**
     * @test
     */
    public function can_create(): void
    {
        $this->authenticateWithPermission(CommentPermission::fromValue(CommentPermission::CREATE_COMMENT));

        $this->brand->makeCurrent();

        $commentData = Comment::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $data = array_merge($commentData, [
            'attachments' => [
                UploadedFile::fake()->image('avatar.jpg'),
            ],
        ]);

        $response = $this->post(route('admin.comments.store'), $data);

        $response->assertCreated();
        $response->assertJson(['data' => $commentData]);
    }

    /**
     * @test
     */
    public function can_not_create(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $commentData = Comment::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $data = array_merge($commentData, [
            'attachments' => [
                UploadedFile::fake()->image('avatar.jpg'),
            ],
        ]);

        $response = $this->post(route('admin.comments.store'), $data);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('admin.comments.store'));

        $response->assertUnauthorized();
    }
}
