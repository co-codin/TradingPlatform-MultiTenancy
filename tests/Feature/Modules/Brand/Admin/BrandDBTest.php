<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Brand\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Modules\Brand\Enums\AllowedDBTables;
use Modules\Brand\Enums\BrandPermission;
use Modules\Brand\Jobs\CreateSchemaJob;
use Modules\Brand\Jobs\MigrateStructureJob;
use Modules\Brand\Models\Brand;
use Modules\Brand\Services\BrandDBService;
use Illuminate\Foundation\Testing\TestCase;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class BrandDBTest extends BrandTestCase
{
    /**
     * @test
     */
    public function test_import(): void
    {
        $user = User::whereEmail('test@service.com')->first() ??
            User::factory()->withParent()->create([
                'email' => 'test@service.com',
            ]);

        $permission = Permission::where(
                'name',
                BrandPermission::fromValue(
                    BrandPermission::VIEW_BRANDS
                )->value
            )->first() ??
            Permission::factory()->create([
                'name' => BrandPermission::fromValue(BrandPermission::VIEW_BRANDS)->value,
            ]);

        $user->givePermissionTo($permission);

        $this->brand->users()->attach($user);

        $response = $this->actingAs($user, User::DEFAULT_AUTH_GUARD)->post(
            route('admin.brands.db.import', ['brand' => $this->brand]),
            [
                'modules' => array_values(['Department' => 'Department',
                    'Desk' => 'Desk',
                    'Geo' => 'Geo',
                    'Language' => 'Language',
                    'Role' => 'Role',
                    'Token' => 'Token', 'User']),
            ],
            [
                'Tenant' => $this->brand->slug,
            ]
        );

        $response->assertStatus(ResponseAlias::HTTP_ACCEPTED);
    }
}
