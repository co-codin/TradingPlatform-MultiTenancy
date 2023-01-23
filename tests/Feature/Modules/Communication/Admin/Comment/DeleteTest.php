<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Comment;

use Modules\Communication\Enums\CommentPermission;
use Modules\Communication\Models\Comment;
use Modules\Geo\Database\Seeders\GeoDatabaseSeeder;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class DeleteTest extends BrandTestCase
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
    public function can_delete(): void
    {
        $this->authenticateWithPermission(CommentPermission::fromValue(CommentPermission::DELETE_COMMENT));

        $this->brand->makeCurrent();

        $comment = Comment::factory()->create();

        $this->brand->makeCurrent();

        $response = $this->delete(route('admin.comments.destroy', ['comment' => $comment]));

        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function cant_delete(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $comment = Comment::factory()->create();

        $this->brand->makeCurrent();

        $response = $this->delete(route('admin.comments.destroy', ['comment' => $comment]));

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

        $response = $this->delete(route('admin.comments.destroy', ['comment' => $commentId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();
        $comment = Comment::factory()->create();
        $response = $this->delete(route('admin.comments.destroy', ['comment' => $comment]));

        $response->assertUnauthorized();
    }
}
