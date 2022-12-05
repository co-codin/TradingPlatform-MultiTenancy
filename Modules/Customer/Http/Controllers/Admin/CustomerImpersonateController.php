<?php

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CustomerImpersonateController extends Controller
{
    public function impersonate(int $customer)
    {
        // only logics
        Auth::guard('customer')->onceUsingId($customer);
    }
}
