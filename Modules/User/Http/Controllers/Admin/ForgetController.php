<?php
declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\User\Http\Requests\ForgetPasswordRequest;

class ForgetController extends Controller
{
    public function forget(ForgetPasswordRequest $request)
    {
        $request->validated();
    }
}
