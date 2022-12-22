<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Comment;

use Illuminate\Http\UploadedFile;
use Modules\Communication\Enums\CommentPermission;
use Modules\Communication\Models\Comment;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_update(): void
    {
        $this->authenticateWithPermission(CommentPermission::fromValue(CommentPermission::EDIT_COMMENT));

        $this->brand->makeCurrent();

        $comment = Comment::factory()->create();
        $commentData = Comment::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $data = array_merge($commentData, [
            'attachments' => [
                UploadedFile::fake()->image('avatar.jpg'),
            ],
        ]);

        $response = $this->patch(route('admin.comments.update', ['comment' => $comment]), $data);

        $response->assertOk();
        $response->assertJson(['data' => $commentData]);
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $comment = Comment::factory()->create();
        $data = Comment::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(route('admin.comments.update', ['comment' => $comment]), $data->toArray());

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $commentId = Comment::orderByDesc('id')->first()?->id + 1 ?? 1;
        $data = Comment::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(route('admin.comments.update', ['comment' => $commentId]),
            $data->toArray());

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();
        $comment = Comment::factory()->create();

        $response = $this->patch(route('admin.comments.update', ['comment' => $comment]));

        $response->assertUnauthorized();
    }
}
