<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Call;

use Modules\Communication\Enums\CallPermission;
use Modules\Communication\Models\Call;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class DeleteTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can delete call.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_delete_call(): void
    {
        $this->authenticateWithPermission(CallPermission::fromValue(CallPermission::DELETE_COMMUNICATION_CALL));

        $this->brand->makeCurrent();

        $call = $this->brand->execute(function () {
            return Call::factory()->make();
        });
        $call->save();

        $response = $this->deleteJson(route('admin.communication.call.destroy', ['call' => $call->id]));

        $response->assertNoContent();
    }

    /**
     * Test authorized user can`t delete call.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_cant_delete_call(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $call = $this->brand->execute(function () {
            return Call::factory()->make();
        });
        $call->save();

        $response = $this->deleteJson(route('admin.communication.call.destroy', ['call' => $call->id]));

        $response->assertForbidden();
    }

    /**
     * Test authorized user can delete not found call.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_delete_not_found_call(): void
    {
        $this->authenticateWithPermission(CallPermission::fromValue(CallPermission::DELETE_COMMUNICATION_CALL));

        $this->brand->makeCurrent();

        $call = Call::orderByDesc('id')->first()?->id + 1 ?? 1;
        $response = $this->delete(route('admin.communication.call.destroy', ['call' => $call]));

        $response->assertNotFound();
    }

    /**
     * Test unauthorized user can`t delete call.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $call = $this->brand->execute(function () {
            return Call::factory()->make();
        });
        $call->save();

        $response = $this->patchJson(route('admin.communication.call.destroy', ['call' => $call->id]));

        $response->assertUnauthorized();
    }
}
