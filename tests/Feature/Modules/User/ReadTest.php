<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User;

use Modules\Department\Models\Department;
use Modules\Desk\Models\Desk;
use Modules\Geo\Models\Country;
use Modules\Language\Models\Language;
use Modules\User\Models\User;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use HasAuth;
    use UsesMultitenancyConfig;

    /**
     * @test
     */
    public function admin_can_view_any()
    {
        User::factory(5)->create();

        $this->authenticateAdmin();

        $response = $this->get(route('admin.users.index'));

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'username',
                    'first_name',
                    'last_name',
                    'email',
                    'is_active',
                    'target',
                    '_lft',
                    '_rgt',
                    'parent_id',
                    'deleted_at',
                    'last_login',
                    'created_at',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function admin_can_view_any_with_relations()
    {
        $users = User::factory(2)->create();

        $this->authenticateAdmin();

        $relations = [
            'roles',
            'roles.permissions',
            'permissions',
            'parent',
            'ancestors',
            'descendants',
            'children',
            'brands',
            'displayOptions',
            'desks',
            'departments',
            'languages',
            'affiliate',
            'comProvider',
        ];

        $this->makeCurrentTenantAndSetHeader();

        $this->brand->users()->sync($users);

        /** @var User $user */
        foreach ($users as $user) {
            $user->desks()->sync(Desk::factory(2)->create());
            $user->departments()->sync(Department::factory(2)->create());
            $user->languages()->sync(Language::take(2)->get() ?? Language::factory(2)->create());
            $user->countries()->sync(Country::take(2)->get() ?? Country::factory(2)->create());
        }

        $response = $this->get(
            route(
                'admin.users.index',
                [
                    'include' => implode(',', $relations),
                    'filter' => [
                        'id' => implode(',', $users->pluck('id')->toArray()),
                        'desks.id' => $users->first()->desks()->first()->id,
                        'departments.id' => $users->first()->departments()->first()->id,
                        'languages.id' => $users->first()->languages()->first()->id,
                        'countries.id' => $users->first()->countries()->first()->id,
                    ],
                ]
            )
        );

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'username',
                    'first_name',
                    'last_name',
                    'email',
                    'is_active',
                    'target',
                    '_lft',
                    '_rgt',
                    'parent_id',
                    'deleted_at',
                    'last_login',
                    'created_at',
                    'roles',
                    'ancestors',
                    'descendants',
                    'brands',
                    'display_options',
                    'desks',
                    'departments',
                    'languages',
                    'affiliate',
                    'com_provider',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function admin_can_view_any_with_relations_without_tenant_header()
    {
        $users = User::factory(2)->create();

        $this->authenticateAdmin();

        $relations = [
            'roles',
            'roles.permissions',
            'permissions',
            'parent',
            'ancestors',
            'descendants',
            'children',
            'brands',
            'displayOptions',
            'desks',
            'departments',
            'languages',
            'affiliate',
            'comProvider',
        ];

        $this->brand->makeCurrent();

        $this->brand->users()->sync($users);

        /** @var User $user */
        foreach ($users as $user) {
            $user->desks()->sync($desks = Desk::factory(2)->create());
            $user->departments()->sync($departments = Department::factory(2)->create());
            $user->languages()->sync($languages = Language::take(2)->get() ?? Language::factory(2)->create());
            $user->countries()->sync($countries = Country::take(2)->get() ?? Country::factory(2)->create());
        }

        $this->brand->forget();

        $response = $this->get(
            route(
                'admin.users.index',
                [
                    'include' => implode(',', $relations),
                    'filter' => [
                        'id' => implode(',', $users->pluck('id')->toArray()),
                        'desks.id' => $desks->first()->id,
                        'departments.id' => $departments->first()->id,
                        'languages.id' => $languages->first()->id,
                        'countries.id' => $countries->first()->id,
                    ],
                ]
            )
        );

        $response->assertServerError();
    }

    /**
     * @test
     */
    public function admin_can_view(): void
    {
        $this->authenticateAdmin();

        $user = User::factory()->create();

        $response = $this->get(route('admin.users.show', ['worker' => $user]));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'username',
                'first_name',
                'last_name',
                'email',
                'is_active',
                'target',
                '_lft',
                '_rgt',
                'parent_id',
                'deleted_at',
                'last_login',
                'created_at',
            ],
        ]);
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $user = User::factory()->create();

        $this->authenticateUser();

        $response = $this->get(route('admin.users.show', ['worker' => $user]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function user_can_view_not_found(): void
    {
        $this->authenticateAdmin();

        $userId = User::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('admin.users.show', ['worker' => $userId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function not_unauthorized_view_any(): void
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('admin.users.show', ['worker' => $user]));

        $response->assertUnauthorized();
    }
}
