<?php

declare(strict_types=1);

namespace Tests;

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\Brand\Models\Brand;
use Modules\Role\Models\Permission;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\DisplayOption;
use Modules\User\Models\User;
use Tests\Traits\HasAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions, HasAuth;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        try {
            $user = User::first() ?? User::factory()->create();
            $brand = Brand::factory()->create();

            $user->givePermissionTo(Permission::where('name', UserPermission::fromValue(UserPermission::VIEW_USERS))->first());

            $response = $this->actingAs($user)->get(route('admin.brands.index'));
            dd($response->json());
            dd($user);
            dd('s');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
