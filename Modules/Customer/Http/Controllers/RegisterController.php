<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Customer\Dto\CustomerDto;
use Modules\Customer\Http\Requests\CustomerRegisterRequest;
use Modules\Customer\Services\CustomerStorage;

final class RegisterController extends Controller
{
    public function __construct(
        protected CustomerStorage $customerStorage,
    ) {
    }

    public function register(CustomerRegisterRequest $request)
    {
        $customer = $this->customerStorage->store(CustomerDto::fromFormRequest($request));

        auth('web-customer')->login($customer);

        $request->session()->regenerate();

        return response()->noContent();
    }
}
