<?php

namespace Tests\Feature\Modules\User\DisplayOption;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Geo\Enums\CountryPermission;
use Modules\Geo\Models\Country;
use Modules\Role\Models\Permission;
use Modules\User\Enums\UserDisplayOptionPermission;
use Modules\User\Models\DisplayOption;
use Modules\User\Models\User;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can delete display option.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_delete(): void
    {
        $this->authenticateWithPermission(
            UserDisplayOptionPermission::fromValue(
                UserDisplayOptionPermission::DELETE_USER_DISPLAY_OPTIONS
            )
        );

        $displayOption = DisplayOption::factory()->create(['user_id' => $this->getUser()->id]);

        $response = $this->delete(
            route('admin.users.display-options.destroy', ['worker' => $this->getUser()->id, 'display_option' => $displayOption->id]),
        );

        $response->assertNoContent();
    }

    /**
     * Test unauthorized user can`t delete display option.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_delete(): void
    {
        $user = User::factory()->create();

        $displayOption = DisplayOption::factory()->create(['user_id' => $user->id]);

        $response = $this->delete(
            route('admin.users.display-options.destroy', ['worker' => $user->id, 'display_option' => $displayOption->id]),
        );

        $response->assertUnauthorized();
    }
}
