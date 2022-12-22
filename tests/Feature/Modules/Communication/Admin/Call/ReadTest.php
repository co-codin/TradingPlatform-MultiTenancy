<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Call;

use Modules\Communication\Enums\CallPermission;
use Modules\Communication\Models\Call;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can get call list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_call_list(): void
    {
        $this->authenticateWithPermission(CallPermission::fromValue(CallPermission::VIEW_COMMUNICATION_CALL));

        $this->brand->makeCurrent();

        $call = $this->brand->execute(function () {
            return Call::factory()->make();
        });
        $call->save();

        $response = $this->getJson(route('admin.communication.call.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [$call->toArray()],
        ]);
    }

    /**
     * Test unauthorized user cant get call list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_cant_get_call_list(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $call = $this->brand->execute(function () {
            return Call::factory()->make();
        });
        $call->save();

        $response = $this->getJson(route('admin.communication.call.index'));

        $response->assertForbidden();
    }

    /**
     * Test unauthorized user get call list.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_get_call_list(): void
    {
        $this->brand->makeCurrent();

        $call = $this->brand->execute(function () {
            return Call::factory()->make();
        });
        $call->save();

        $response = $this->getJson(route('admin.communication.call.index'));

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user can get call by ID.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_call(): void
    {
        $this->authenticateWithPermission(CallPermission::fromValue(CallPermission::VIEW_COMMUNICATION_CALL));

        $this->brand->makeCurrent();

        $call = $this->brand->execute(function () {
            return Call::factory()->make();
        });
        $call->save();

        $response = $this->getJson(route('admin.communication.call.show', ['call' => $call->id]));

        $response->assertOk();

        $response->assertJson(['data' => $call->toArray()]);
    }

    /**
     * Test authorized user can get call by ID.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_cant_get_call(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $call = $this->brand->execute(function () {
            return Call::factory()->make();
        });
        $call->save();

        $response = $this->getJson(route('admin.communication.call.show', ['call' => $call->id]));

        $response->assertForbidden();
    }

    /**
     * Test authorized user can get not found call by ID.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_not_found_call(): void
    {
        $this->authenticateWithPermission(CallPermission::fromValue(CallPermission::VIEW_COMMUNICATION_CALL));

        $this->brand->makeCurrent();

        $callId = Call::orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->getJson(route('admin.communication.call.show', ['call' => $callId]));

        $response->assertNotFound();
    }

    /**
     * Test unauthorized user can get call by ID.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_can_get_call(): void
    {
        $this->brand->makeCurrent();

        $call = $this->brand->execute(function () {
            return Call::factory()->make();
        });
        $call->save();

        $response = $this->getJson(route('admin.communication.call.show', ['call' => $call->id]));

        $response->assertUnauthorized();
    }
}
