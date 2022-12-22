<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Comment;

use Modules\Communication\Enums\CommentPermission;
use Modules\Communication\Models\Comment;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_view_any(): void
    {
        $this->authenticateWithPermission(CommentPermission::fromValue(CommentPermission::VIEW_COMMENT));

        $this->brand->makeCurrent();

        $comments = Comment::factory(10)->create();

        $this->brand->makeCurrent();

        $response = $this->get(route('admin.comments.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => $comments->only('id')->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function can_not_view_any(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $response = $this->get(route('admin.comments.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_view(): void
    {
        $this->authenticateWithPermission(CommentPermission::fromValue(CommentPermission::VIEW_COMMENT));

        $this->brand->makeCurrent();
        $comment = Comment::factory()->create();

        $response = $this->get(route('admin.comments.show', ['comment' => $comment]));

        $response->assertOk();
        $response->assertJson(['data' => $comment->toArray()]);
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $comment = Comment::factory()->create();

        $response = $this->get(route('admin.comments.show', ['comment' => $comment]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $commentId = Comment::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('admin.comments.show', ['comment' => $commentId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized_view_any(): void
    {
        $response = $this->get(route('admin.comments.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $this->brand->makeCurrent();
        $comment = Comment::factory()->create();

        $response = $this->get(route('admin.comments.show', ['comment' => $comment]));

        $response->assertUnauthorized();
    }
}
