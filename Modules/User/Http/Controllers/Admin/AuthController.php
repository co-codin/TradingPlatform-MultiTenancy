<?php

namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Http\Resources\AuthUserResource;
use Modules\User\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::query()
            ->where('email', $request->input('email'))
            ->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'user' => new AuthUserResource($user),
            'token' => $user->createToken('api')->plainTextToken,
        ];
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response([], 200);
    }

    public function user()
    {
        return new AuthUserResource(auth()->user());
    }
}
